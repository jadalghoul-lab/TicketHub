<?php

namespace App\Services;

use App\Models\Order;
use App\Models\TicketReservation;
use App\Models\TicketType;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TicketReservationService
{
    /**
     * Duration in minutes a reservation is held before auto-release.
     */
    const HOLD_MINUTES = 10;

    // ─── Reserve ────────────────────────────────────────────────────────────────

    /**
     * Attempt to create a temporary reservation for the given ticket type.
     *
     * Uses DB::transaction + lockForUpdate to prevent race conditions at
     * the database level — even when two requests arrive simultaneously.
     *
     * @throws \Exception If not enough tickets are available.
     */
    public function reserve(TicketType $ticketType, int $quantity, int $userId, string $sessionId): TicketReservation
    {
        return DB::transaction(function () use ($ticketType, $quantity, $userId, $sessionId) {

            // 1. Lock the ticket_type row so no concurrent request can read a stale quantity
            $lockedType = TicketType::lockForUpdate()->findOrFail($ticketType->id);

            // 2. Calculate how many are already held by OTHER active reservations
            $heldByOthers = TicketReservation::active()
                ->where('ticket_type_id', $lockedType->id)
                ->where(function ($q) use ($userId) {
                    // Exclude the current user's own existing reservation so we don't double-count
                    $q->where('user_id', '!=', $userId)->orWhereNull('user_id');
                })
                ->sum('quantity');

            $available = $lockedType->quantity - $heldByOthers;

            if ($available < $quantity) {
                throw new \Exception(
                    "Sorry, only {$available} ticket(s) are currently available. ".
                    'Some tickets may be temporarily held by other customers.'
                );
            }

            // 3. Release any previous (unexpired) reservation the same user had for this ticket type
            //    This handles the case where a user goes Back and tries again with a different quantity
            TicketReservation::active()
                ->where('ticket_type_id', $lockedType->id)
                ->where('user_id', $userId)
                ->delete();

            // 4. Create the new reservation
            $reservation = TicketReservation::create([
                'ticket_type_id' => $lockedType->id,
                'user_id' => $userId,
                'session_id' => $sessionId,
                'quantity' => $quantity,
                'expires_at' => now()->addMinutes(self::HOLD_MINUTES),
            ]);

            Log::info("Reservation #{$reservation->id} created: {$quantity}x TicketType#{$lockedType->id} for User#{$userId}");

            return $reservation;
        });
    }

    // ─── Release ────────────────────────────────────────────────────────────────

    /**
     * Manually release a reservation (e.g., user clicked Back or cancelled).
     */
    public function release(int $reservationId): void
    {
        $reservation = TicketReservation::find($reservationId);

        if ($reservation && is_null($reservation->order_id)) {
            $reservation->delete();
            Log::info("Reservation #{$reservationId} released manually.");
        }
    }

    // ─── Confirm ────────────────────────────────────────────────────────────────

    /**
     * Confirm a reservation after successful payment.
     * Links the reservation to the real Order so it is no longer considered "temporary".
     */
    public function confirmReservation(int $reservationId, Order $order): void
    {
        TicketReservation::where('id', $reservationId)
            ->whereNull('order_id')
            ->update(['order_id' => $order->id]);

        Log::info("Reservation #{$reservationId} confirmed for Order #{$order->id}");
    }

    // ─── Available Quantity ──────────────────────────────────────────────────────

    /**
     * Return the real available quantity for a ticket type,
     * accounting for active (temporary) reservations by OTHER users.
     *
     * @param  int|null  $excludeUserId  Exclude this user's own reservation from the held count
     */
    public function getAvailableQuantity(TicketType $ticketType, ?int $excludeUserId = null): int
    {
        $heldQuery = TicketReservation::active()
            ->where('ticket_type_id', $ticketType->id);

        if ($excludeUserId) {
            $heldQuery->where(function ($q) use ($excludeUserId) {
                $q->where('user_id', '!=', $excludeUserId)->orWhereNull('user_id');
            });
        }

        $held = $heldQuery->sum('quantity');

        return max(0, $ticketType->quantity - $held);
    }

    // ─── Cleanup ────────────────────────────────────────────────────────────────

    /**
     * Delete all expired, unconfirmed reservations.
     * Called by the scheduler every minute.
     */
    public function cleanupExpired(): int
    {
        $count = TicketReservation::expired()->count();
        TicketReservation::expired()->delete();

        if ($count > 0) {
            Log::info("TicketReservationService: Cleaned up {$count} expired reservation(s).");
        }

        return $count;
    }
}
