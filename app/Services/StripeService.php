<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Stripe\StripeClient;

class StripeService
{
    protected $stripe;

    public function __construct()
    {
        $this->stripe = new StripeClient(config('services.stripe.secret'));
    }

    /**
     * Process a refund for a payment intent.
     */
    public function refund(string $paymentIntentId, ?int $amount = null, ?string $reason = null): array
    {
        // Handle mock IDs for local development testing
        if (str_starts_with($paymentIntentId, 'pi_mock_')) {
            return [
                'success' => true,
                'refund_id' => 'ref_mock_'.str()->random(10),
                'status' => 'succeeded',
            ];
        }

        try {
            $params = [
                'payment_intent' => $paymentIntentId,
            ];

            if ($amount) {
                $params['amount'] = $amount;
            }

            if ($reason) {
                $params['reason'] = $reason;
            }

            $refund = $this->stripe->refunds->create($params);

            return [
                'success' => true,
                'refund_id' => $refund->id,
                'status' => $refund->status,
            ];
        } catch (\Exception $e) {
            Log::error('Stripe Refund Error: '.$e->getMessage(), [
                'payment_intent_id' => $paymentIntentId,
                'exception' => $e,
            ]);

            return [
                'success' => false,
                'message' => $e->getMessage(),
            ];
        }
    }
}
