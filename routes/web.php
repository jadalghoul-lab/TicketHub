<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Public\HomeController;
use App\Http\Controllers\Public\EventController;
use App\Http\Controllers\Public\TicketController;

// Public routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/events', [EventController::class, 'index'])->name('public.events.index');
Route::get('/events/{slug}', [EventController::class, 'show'])->name('public.events.show');
Route::get('/support', [\App\Http\Controllers\Public\SupportController::class, 'index'])->name('public.support');
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

    Route::get('dashboard', [\App\Http\Controllers\Public\CustomerDashboardController::class, 'index'])->name('dashboard');

    Route::middleware('role:admin')->group(function () {
        Route::get('admin/dashboard', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('admin.dashboard');
        Route::get('admin/export-report', [\App\Http\Controllers\Admin\DashboardController::class, 'export'])->name('admin.export');
        Route::get('admin/organizers', \App\Livewire\Admin\OrganizerManager::class)->name('admin.organizers.index');
        Route::get('admin/events', \App\Livewire\Admin\EventManager::class)->name('admin.events.index');
        Route::get('admin/payouts', \App\Livewire\Admin\PayoutManager::class)->name('admin.payouts');
    });

    Route::middleware('role:organizer')->group(function () {
        Route::get('organizer/dashboard', [\App\Http\Controllers\Organizer\DashboardController::class, 'index'])->name('organizer.dashboard');
        Route::get('organizer/events', \App\Livewire\Organizer\EventManager::class)->name('organizer.events.index');
        Route::get('organizer/events/{event}/tickets', \App\Livewire\Organizer\TicketManager::class)->name('organizer.events.tickets');
        Route::get('organizer/events/{event}/scanner', \App\Livewire\Organizer\Scanner::class)->name('organizer.scanner');
        Route::get('organizer/global-scanner', \App\Livewire\Organizer\GlobalScanner::class)->name('organizer.global-scanner');
        Route::get('organizer/refunds', \App\Livewire\Organizer\RefundManager::class)->name('organizer.refunds');
        Route::get('organizer/coupons', \App\Livewire\Organizer\CouponManager::class)->name('organizer.coupons');
        Route::get('organizer/payouts', \App\Livewire\Organizer\PayoutManager::class)->name('organizer.payouts');
    });
});

require __DIR__.'/settings.php';
