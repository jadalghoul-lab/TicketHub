<?php

namespace App\Services;

use App\Models\Coupon;
use App\Models\User;
use App\Models\Event;
use Illuminate\Support\Facades\DB;

class CouponService
{
    /**
     * Validate a coupon for a specific event and user.
     */
    public function validate(string $code, int $eventId, int $userId): array
    {
        $coupon = Coupon::where('code', $code)->first();

        if (!$coupon) {
            return ['valid' => false, 'message' => 'Invalid coupon code.'];
        }

        // Check expiration
        if ($coupon->expires_at && $coupon->expires_at->isPast()) {
            return ['valid' => false, 'message' => 'This coupon has expired.'];
        }

        // Check event-specific
        if ($coupon->event_id && $coupon->event_id !== $eventId) {
            return ['valid' => false, 'message' => 'This coupon is not valid for this event.'];
        }

        // Check max usages
        if ($coupon->max_usages && $coupon->usages_count >= $coupon->max_usages) {
            return ['valid' => false, 'message' => 'This coupon has reached its maximum usage limit.'];
        }

        // Check one per customer
        $alreadyUsed = DB::table('coupon_usages')
            ->where('coupon_id', $coupon->id)
            ->where('user_id', $userId)
            ->exists();

        if ($alreadyUsed) {
            return ['valid' => false, 'message' => 'You have already used this coupon.'];
        }

        return [
            'valid' => true,
            'coupon' => $coupon,
            'message' => 'Coupon applied successfully!'
        ];
    }

    /**
     * Calculate the discount amount.
     */
    public function calculateDiscount(Coupon $coupon, float $amount): float
    {
        if ($coupon->type === 'percentage') {
            return ($amount * $coupon->value) / 100;
        }

        return min($coupon->value, $amount);
    }

    /**
     * Record coupon usage.
     */
    public function recordUsage(int $couponId, int $userId, int $orderId): void
    {
        DB::table('coupon_usages')->insert([
            'coupon_id' => $couponId,
            'user_id' => $userId,
            'order_id' => $orderId,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        Coupon::where('id', $couponId)->increment('usages_count');
    }
}
