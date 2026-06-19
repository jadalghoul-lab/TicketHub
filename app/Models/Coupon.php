<?php

namespace App\Models;

use App\Traits\BelongsToOrganizer;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Coupon extends Model
{
    use BelongsToOrganizer, HasFactory, SoftDeletes;

    protected $fillable = [
        'organizer_id',
        'event_id',
        'code',
        'type',
        'value',
        'expires_at',
        'max_usages',
        'usages_count',
        'once_per_customer',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'value' => 'decimal:2',
        'usages_count' => 'integer',
        'max_usages' => 'integer',
        'once_per_customer' => 'boolean',
    ];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function isValid($user = null)
    {
        if ($this->expires_at && $this->expires_at->isPast()) {
            return false;
        }

        if ($this->max_usages && $this->usages_count >= $this->max_usages) {
            return false;
        }

        if ($this->once_per_customer && $user) {
            // Check if this user has already used this coupon in any successful order
            $usedCount = Order::where('user_id', $user->id)
                ->where('coupon_id', $this->id)
                ->where('status', 'paid')
                ->count();

            if ($usedCount > 0) {
                return false;
            }
        }

        return true;
    }

    public function calculateDiscount($originalPrice)
    {
        if ($this->type === 'percentage') {
            return $originalPrice * ($this->value / 100);
        }

        return min($this->value, $originalPrice);
    }
}
