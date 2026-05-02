<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\BelongsToOrganizer;

class Coupon extends Model
{
    use SoftDeletes, BelongsToOrganizer;

    protected $fillable = [
        'organizer_id',
        'event_id',
        'code',
        'type',
        'value',
        'expires_at',
        'max_usages',
        'usages_count',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'value' => 'decimal:2',
        'usages_count' => 'integer',
        'max_usages' => 'integer',
    ];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function isValid()
    {
        if ($this->expires_at && $this->expires_at->isPast()) {
            return false;
        }

        if ($this->max_usages && $this->usages_count >= $this->max_usages) {
            return false;
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
