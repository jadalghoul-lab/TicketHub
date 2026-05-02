<?php

namespace App\Http\Controllers\Webhook;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Ticket;
use App\Models\TicketType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Stripe\Stripe;
use Stripe\Webhook;
use Illuminate\Support\Str;

use App\Services\CheckoutService;

class StripeWebhookController extends Controller
{
    public function handle(Request $request, CheckoutService $checkoutService)
    {
        Stripe::setApiKey(config('services.stripe.secret'));

        $payload = $request->getContent();
        $sig_header = $request->header('Stripe-Signature');
        $endpoint_secret = config('services.stripe.webhook_secret');

        try {
            $event = Webhook::constructEvent(
                $payload, $sig_header, $endpoint_secret
            );
        } catch (\UnexpectedValueException $e) {
            return response()->json(['error' => 'Invalid payload'], 400);
        } catch (\Stripe\Exception\SignatureVerificationException $e) {
            return response()->json(['error' => 'Invalid signature'], 400);
        }

        // Handle the event
        switch ($event->type) {
            case 'checkout.session.completed':
            case 'payment_intent.succeeded':
                $checkoutService->fulfillOrder($event->data->object);
                break;
            
            case 'payment_intent.payment_failed':
                $checkoutService->handleFailure($event->data->object);
                break;

            case 'charge.refunded':
                $checkoutService->handleRefund($event->data->object);
                break;

            default:
                Log::info('Received unhandled Stripe event: ' . $event->type);
        }

        return response()->json(['status' => 'success']);
    }
}
