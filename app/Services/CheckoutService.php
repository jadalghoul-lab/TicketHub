<?php

namespace App\Services;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\Ticket;
use App\Models\TicketType;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class CheckoutService
{
    /**
     * Fulfill an order after successful payment.
     */
    public function fulfillOrder($session)
    {
        $orderId = $session->metadata->order_id ?? null;
        $order = Order::find($orderId);

        if (!$order || $order->status === 'paid') {
            return;
        }

        DB::transaction(function () use ($order, $session) {
            // 1. Update Order
            $order->update([
                'status' => 'paid',
                'payment_intent_id' => $session->payment_intent ?? $order->payment_intent_id
            ]);

            // 2. Create Payment Record
            $amount = isset($session->amount_total) ? $session->amount_total : $session->amount;
            Payment::create([
                'order_id' => $order->id,
                'stripe_payment_id' => $session->payment_intent ?? $session->id,
                'amount' => $amount / 100,
                'currency' => strtoupper($session->currency),
                'status' => 'succeeded',
            ]);

            // 3. Create Tickets
            $ticketTypeId = $session->metadata->ticket_type_id;
            $quantity = $session->metadata->quantity;
            
            // USE lockForUpdate to prevent race conditions (overselling)
            $ticketType = TicketType::where('id', $ticketTypeId)->lockForUpdate()->first();

            if (!$ticketType || $ticketType->quantity < $quantity) {
                Log::error("Overselling prevented for Order #{$order->id}. Required: {$quantity}, Available: {$ticketType->quantity}");
                throw new \Exception("Not enough tickets available to fulfill order.");
            }

            for ($i = 0; $i < $quantity; $i++) {
                Ticket::create([
                    'order_id' => $order->id,
                    'event_id' => $order->event_id,
                    'ticket_type_id' => $ticketTypeId,
                    'user_id' => $order->user_id,
                    'uuid' => (string) Str::uuid(),
                    'ticket_number' => 'TKT-' . strtoupper(Str::random(10)),
                    'status' => 'valid',
                ]);
            }

            // 4. Update Stock
            $ticketType->decrement('quantity', $quantity);

            // 5. Record Coupon Usage if applicable
            if ($order->coupon_id) {
                app(\App\Services\CouponService::class)->recordUsage(
                    $order->coupon_id,
                    $order->user_id,
                    $order->id
                );
            }

            // 6. Send Email (via Queue)
            \App\Jobs\SendOrderTicketsJob::dispatch($order);

            // 7. Send In-App Notification
            $order->user->notify(new \App\Notifications\OrderConfirmed($order));
        });

        Log::info("Order #{$order->id} fulfilled successfully via CheckoutService.");
    }

    /**
     * Handle payment failure.
     */
    public function handleFailure($intent)
    {
        $order = Order::where('payment_intent_id', $intent->id)->first();
        if ($order) {
            $order->update(['status' => 'failed']);
            Log::warning("Order #{$order->id} payment failed.");
        }
    }

    /**
     * Handle refund.
     */
    public function handleRefund($charge)
    {
        $intentId = $charge->payment_intent;
        $order = Order::where('payment_intent_id', $intentId)->first();
        if ($order) {
            $order->update(['status' => 'refunded']);
            Ticket::where('order_id', $order->id)->update(['status' => 'refunded']);
            Log::info("Order #{$order->id} refunded.");
        }
    }
}
