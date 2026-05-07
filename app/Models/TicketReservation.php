<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class TicketReservation extends Model
{
    protected $fillable = [
        'ticket_type_id',
        'user_id',
        'session_id',
        'quantity',
        'expires_at',
        'order_id',
    ];

    protected function casts(): array
    {
        return [
            'expires_at' => 'datetime',
            'quantity'   => 'integer',
        ];
    }

    // ─── Relationships ──────────────────────────────────────────────────────────

    public function ticketType()
    {
        return $this->belongsTo(TicketType::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    // ─── Scopes ─────────────────────────────────────────────────────────────────

    /**
     * Only reservations that have NOT yet expired and are not confirmed (no order).
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->whereNull('order_id')
                     ->where('expires_at', '>', now());
    }

    /**
     * Reservations that have expired and were never confirmed.
     */
    public function scopeExpired(Builder $query): Builder
    {
        return $query->whereNull('order_id')
                     ->where('expires_at', '<=', now());
    }

    // ─── Helpers ────────────────────────────────────────────────────────────────

    public function isExpired(): bool
    {
        return $this->expires_at->isPast();
    }

    public function secondsRemaining(): int
    {
        return max(0, now()->diffInSeconds($this->expires_at, false));
    }
}
