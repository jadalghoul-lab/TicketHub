<?php

namespace App\Services;

use App\Models\Order;
use App\Models\PayoutRequest;
use App\Models\Organizer;

class PayoutService
{
    /**
     * Calculate the available balance for an organizer.
     * Available Balance = (Total Paid Orders Revenue) - (Total requested payouts)
     */
    public function getAvailableBalance(int $organizerId): float
    {
        // 1. Total Revenue from successful orders
        // Exclude refunded or failed orders
        $totalRevenue = Order::where('organizer_id', $organizerId)
            ->where('status', 'paid')
            ->sum('total_amount');

        // 2. Total requested payouts (Pending, Approved, Paid)
        // We exclude 'rejected' as those funds go back to the available balance.
        $totalPayouts = PayoutRequest::where('organizer_id', $organizerId)
            ->whereIn('status', ['pending', 'approved', 'paid'])
            ->sum('amount');

        return max(0, $totalRevenue - $totalPayouts);
    }

    /**
     * Create a new payout request for an organizer.
     */
    public function requestPayout(Organizer $organizer, float $amount, string $bankDetails): array
    {
        if ($amount <= 0) {
            return ['success' => false, 'message' => 'Invalid payout amount.'];
        }

        $availableBalance = $this->getAvailableBalance($organizer->id);

        if ($amount > $availableBalance) {
            return ['success' => false, 'message' => 'Insufficient funds for this payout request.'];
        }

        $payoutRequest = PayoutRequest::create([
            'organizer_id' => $organizer->id,
            'amount' => $amount,
            'status' => 'pending',
            'bank_details' => $bankDetails,
        ]);

        return ['success' => true, 'message' => 'Payout request submitted successfully.', 'payout' => $payoutRequest];
    }
}
