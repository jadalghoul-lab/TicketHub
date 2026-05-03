<?php

namespace App\Livewire\Organizer;

use App\Models\RefundRequest;
use App\Models\Order;
use App\Models\Ticket;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RefundManager extends Component
{
    public function approve($requestId)
    {
        $request = RefundRequest::with('order.event')->findOrFail($requestId);
        
        // Ensure the organizer owns this event
        if ($request->order->event->organizer_id !== Auth::user()->organizer->id) {
            return;
        }

        $order = $request->order;

        // Trigger Stripe Refund
        if ($order->payment_intent_id) {
            $stripeService = app(\App\Services\StripeService::class);
            // Stripe amounts are in cents
            $amountInCents = (int) ($order->total_amount * 100);
            
            $refundResult = $stripeService->refund($order->payment_intent_id, $amountInCents, 'requested_by_customer');

            if (!$refundResult['success']) {
                session()->flash('error', 'Stripe Refund Failed: ' . $refundResult['message']);
                return;
            }
        }

        DB::transaction(function () use ($request) {
            // 1. Mark request as approved
            $request->update(['status' => 'approved']);

            // 2. Mark order as refunded
            $request->order->update(['status' => 'refunded']);

            // 3. Void all tickets for this order
            Ticket::where('order_id', $request->order_id)->update(['status' => 'refunded']);
        });

        session()->flash('success', 'Refund approved and processed via Stripe. Tickets have been voided.');
    }

    public function reject($requestId, $reason = 'Refund criteria not met.')
    {
        $request = RefundRequest::with('order.event')->findOrFail($requestId);
        
        if ($request->order->event->organizer_id !== Auth::user()->organizer->id) {
            return;
        }

        $request->update(['status' => 'rejected']);
        
        session()->flash('info', 'Refund request rejected.');
    }

    public function render()
    {
        $organizerId = Auth::user()->organizer->id;
        
        $requests = RefundRequest::with(['order.event', 'user'])
            ->whereHas('order.event', function ($query) use ($organizerId) {
                $query->where('organizer_id', $organizerId);
            })
            ->latest()
            ->paginate(10);

        return view('livewire.organizer.refund-manager', [
            'requests' => $requests
        ]);
    }
}
