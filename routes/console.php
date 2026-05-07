<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// ─── Release expired ticket reservations every minute ───────────────────────
// This ensures tickets held by users who abandoned checkout are freed promptly.
Schedule::job(new \App\Jobs\ReleaseExpiredReservationsJob())->everyMinute();
