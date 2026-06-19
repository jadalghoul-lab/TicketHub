<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Public\CustomerDashboardController;
use App\Http\Controllers\Public\EventController;
use App\Http\Controllers\Public\HomeController;
use App\Http\Controllers\Public\SupportController;
use App\Http\Controllers\Public\TicketController;
use App\Http\Controllers\Webhook\StripeWebhookController;
use App\Livewire\Admin\EventManager;
use App\Livewire\Admin\OrganizerManager;
use App\Livewire\Admin\PayoutManager;
use App\Livewire\Organizer\CouponManager;
use App\Livewire\Organizer\GlobalScanner;
use App\Livewire\Organizer\RefundManager;
use App\Livewire\Organizer\Scanner;
use App\Livewire\Organizer\TicketManager;
use App\Livewire\Public\Checkout;
use App\Mail\OrderTicketsMail;
use App\Models\Order;
use Illuminate\Support\Facades\Route;

// Public routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/events', [EventController::class, 'index'])->name('public.events.index');
Route::get('/events/{slug}', [EventController::class, 'show'])->name('public.events.show');
Route::get('/support', [SupportController::class, 'index'])->name('public.support');
Route::post('/webhook/stripe', [StripeWebhookController::class, 'handle']);

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/my-tickets', [TicketController::class, 'index'])->name('public.tickets.index');
    Route::get('/my-tickets/{uuid}', [TicketController::class, 'show'])->name('public.tickets.show');
    Route::get('/my-tickets/{ticket}/pdf', [TicketController::class, 'downloadPdf'])->name('public.tickets.pdf');

    // Checkout Flow
    Route::get('/checkout/{slug}', Checkout::class)->name('public.checkout');
    Route::get('/checkout/success/{order_number}', [EventController::class, 'checkoutSuccess'])->name('public.checkout.success');
    Route::get('/checkout/cancel/{order_number}', [EventController::class, 'checkoutCancel'])->name('public.checkout.cancel');

    // Temporary Mail Preview
    Route::get('/mail-preview', function () {
        $order = Order::with(['user', 'event', 'tickets.ticketType'])->first();
        if (! $order) {
            return 'No orders found in database to preview.';
        }

        return new OrderTicketsMail($order);
    });

    Route::get('dashboard', [CustomerDashboardController::class, 'index'])->name('dashboard');

    Route::middleware('role:admin')->group(function () {
        Route::get('admin/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
        Route::get('admin/export-report', [DashboardController::class, 'export'])->name('admin.export');
        Route::get('admin/organizers', OrganizerManager::class)->name('admin.organizers.index');
        Route::get('admin/events', EventManager::class)->name('admin.events.index');
        Route::get('admin/payouts', PayoutManager::class)->name('admin.payouts');
    });

    Route::middleware('role:organizer')->group(function () {
        Route::get('organizer/dashboard', [App\Http\Controllers\Organizer\DashboardController::class, 'index'])->name('organizer.dashboard');
        Route::get('organizer/events', App\Livewire\Organizer\EventManager::class)->name('organizer.events.index');
        Route::get('organizer/events/{event}/tickets', TicketManager::class)->name('organizer.events.tickets');
        Route::get('organizer/events/{event}/scanner', Scanner::class)->name('organizer.scanner');
        Route::get('organizer/global-scanner', GlobalScanner::class)->name('organizer.global-scanner');
        Route::get('organizer/refunds', RefundManager::class)->name('organizer.refunds');
        Route::get('organizer/coupons', CouponManager::class)->name('organizer.coupons');
        Route::get('organizer/payouts', App\Livewire\Organizer\PayoutManager::class)->name('organizer.payouts');
    });
});

require __DIR__.'/settings.php';
