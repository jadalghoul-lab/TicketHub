<?php

namespace App\Jobs;

use App\Services\TicketReservationService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

class ReleaseExpiredReservationsJob implements ShouldQueue
{
    use Queueable;

    public function handle(TicketReservationService $reservationService): void
    {
        $count = $reservationService->cleanupExpired();
        Log::info("ReleaseExpiredReservationsJob: Released {$count} expired reservation(s).");
    }
}
