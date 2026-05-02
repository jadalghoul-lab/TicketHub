<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Public\HomeController;
use App\Http\Controllers\Public\EventController;
use App\Http\Controllers\Public\TicketController;

// Public routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/events', [EventController::class, 'index'])->name('public.events.index');
Route::get('/events/{slug}', [EventController::class, 'show'])->name('public.events.show');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/my-tickets', [TicketController::class, 'index'])->name('public.tickets.index');
    Route::get('/my-tickets/{uuid}', [TicketController::class, 'show'])->name('public.tickets.show');
    
    Route::view('dashboard', 'dashboard')->name('dashboard');

    Route::middleware('role:admin')->group(function () {
        Route::view('admin/dashboard', 'dashboard')->name('admin.dashboard');
    });

    Route::middleware('role:organizer')->group(function () {
        Route::view('organizer/dashboard', 'dashboard')->name('organizer.dashboard');
    });
});

require __DIR__.'/settings.php';
