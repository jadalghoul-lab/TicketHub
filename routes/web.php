<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Public\HomeController;
use App\Http\Controllers\Public\EventController;
use App\Http\Controllers\Public\TicketController;

// Public routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/events', [EventController::class, 'index'])->name('public.events.index');
Route::get('/events/{slug}', [EventController::class, 'show'])->name('public.events.show');
Route::post('/webhook/stripe', [App\Http\Controllers\Webhook\StripeWebhookController::class, 'handle']);

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/my-tickets', [TicketController::class, 'index'])->name('public.tickets.index');
    Route::get('/my-tickets/{uuid}', [TicketController::class, 'show'])->name('public.tickets.show');
    
    // Checkout Flow
    Route::get('/checkout/{slug}', \App\Livewire\Public\Checkout::class)->name('public.checkout');
    Route::get('/checkout/success/{order_number}', [App\Http\Controllers\Public\EventController::class, 'checkoutSuccess'])->name('public.checkout.success');
    Route::get('/checkout/cancel/{order_number}', [App\Http\Controllers\Public\EventController::class, 'checkoutCancel'])->name('public.checkout.cancel');

    // Temporary Mail Preview
    Route::get('/mail-preview', function () {
        $order = \App\Models\Order::with(['user', 'event', 'tickets.ticketType'])->first();
        if (!$order) return 'No orders found in database to preview.';
        return new \App\Mail\OrderTicketsMail($order);
    });

    Route::view('dashboard', 'dashboard')->name('dashboard');

    Route::middleware('role:admin')->group(function () {
        Route::view('admin/dashboard', 'dashboard')->name('admin.dashboard');
    });

    Route::middleware('role:organizer')->group(function () {
        Route::view('organizer/dashboard', 'dashboard')->name('organizer.dashboard');
    });
});

require __DIR__.'/settings.php';
