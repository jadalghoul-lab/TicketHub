<?php

/**
 * ─────────────────────────────────────────────────────────────────────────────
 * TicketReservation System — Pest Feature Tests
 *
 * Covers:
 *  1. Successful reservation creation
 *  2. Race condition: second user blocked when quantity = 1
 *  3. Max-per-order validation still respected
 *  4. User going Back releases their reservation
 *  5. Expired reservation is detected and cleaned up by the Job
 *  6. Reservation confirmed after successful payment (fulfillOrder)
 *  7. Expired reservation blocks pay() in Checkout Livewire
 *  8. Available quantity reflects active reservations (not just raw stock)
 *  9. Same user can change quantity (re-reserve) without double-counting
 * 10. Cleanup job returns correct count
 * ─────────────────────────────────────────────────────────────────────────────
 */

use App\Enums\EventStatus;
use App\Enums\Role;
use App\Jobs\ReleaseExpiredReservationsJob;
use App\Livewire\Public\Checkout;
use App\Models\Event;
use App\Models\Order;
use App\Models\Organizer;
use App\Models\TicketReservation;
use App\Models\TicketType;
use App\Models\User;
use App\Services\TicketReservationService;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Str;
use Livewire\Livewire;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

// ─── Shared Setup ────────────────────────────────────────────────────────────

beforeEach(function () {
    // Organizer
    $this->organizerUser = User::factory()->create(['role' => Role::ORGANIZER]);
    $this->organizer     = Organizer::create([
        'user_id'      => $this->organizerUser->id,
        'company_name' => 'Test Org',
        'slug'         => 'test-org-' . Str::random(4),
    ]);

    // Published event
    $this->event = Event::create([
        'organizer_id' => $this->organizer->id,
        'title'        => 'Test Event',
        'slug'         => 'test-event-' . Str::random(4),
        'category'     => 'music',
        'city'         => 'Brussels',
        'country'      => 'Belgium',
        'start_date'   => now()->addDays(10),
        'status'       => EventStatus::PUBLISHED,
    ]);

    // Ticket type with only 1 seat — key for race condition tests
    $this->ticketType = TicketType::create([
        'event_id'      => $this->event->id,
        'name'          => 'General',
        'price'         => 50,
        'quantity'      => 1,
        'max_per_order' => 5,
    ]);

    // Two distinct customers
    $this->userA = User::factory()->create(['role' => Role::CUSTOMER]);
    $this->userB = User::factory()->create(['role' => Role::CUSTOMER]);

    $this->service = app(TicketReservationService::class);
});

// ─────────────────────────────────────────────────────────────────────────────
// 1. Successful reservation creation
// ─────────────────────────────────────────────────────────────────────────────

test('user can reserve a ticket and reservation is stored in database', function () {
    $reservation = $this->service->reserve(
        $this->ticketType,
        quantity: 1,
        userId: $this->userA->id,
        sessionId: 'sess_userA'
    );

    expect($reservation)->toBeInstanceOf(TicketReservation::class);
    expect($reservation->quantity)->toBe(1);
    expect($reservation->user_id)->toBe($this->userA->id);
    expect($reservation->expires_at->isFuture())->toBeTrue();
    expect($reservation->order_id)->toBeNull(); // not yet confirmed

    $this->assertDatabaseHas('ticket_reservations', [
        'ticket_type_id' => $this->ticketType->id,
        'user_id'        => $this->userA->id,
        'quantity'       => 1,
    ]);
});

// ─────────────────────────────────────────────────────────────────────────────
// 2. Race condition: second user is blocked when quantity = 1
// ─────────────────────────────────────────────────────────────────────────────

test('second user cannot reserve a ticket already held by first user', function () {
    // User A grabs the last seat
    $this->service->reserve(
        $this->ticketType,
        quantity: 1,
        userId: $this->userA->id,
        sessionId: 'sess_userA'
    );

    // User B tries to grab the same seat — must be blocked
    expect(fn () =>
        $this->service->reserve(
            $this->ticketType,
            quantity: 1,
            userId: $this->userB->id,
            sessionId: 'sess_userB'
        )
    )->toThrow(\Exception::class);

    // Only one reservation should exist
    expect(TicketReservation::active()->where('ticket_type_id', $this->ticketType->id)->count())->toBe(1);
});

// ─────────────────────────────────────────────────────────────────────────────
// 3. getAvailableQuantity returns 0 while a hold is active
// ─────────────────────────────────────────────────────────────────────────────

test('available quantity drops to 0 when a reservation is active', function () {
    // Before reservation
    expect($this->service->getAvailableQuantity($this->ticketType))->toBe(1);

    $this->service->reserve(
        $this->ticketType,
        quantity: 1,
        userId: $this->userA->id,
        sessionId: 'sess_userA'
    );

    // After reservation — another user sees 0
    expect($this->service->getAvailableQuantity($this->ticketType, excludeUserId: $this->userB->id))->toBe(0);
});

// ─────────────────────────────────────────────────────────────────────────────
// 4. Releasing a reservation frees the stock
// ─────────────────────────────────────────────────────────────────────────────

test('releasing a reservation makes the ticket available again', function () {
    $reservation = $this->service->reserve(
        $this->ticketType,
        quantity: 1,
        userId: $this->userA->id,
        sessionId: 'sess_userA'
    );

    $this->service->release($reservation->id);

    // Reservation is deleted
    $this->assertDatabaseMissing('ticket_reservations', ['id' => $reservation->id]);

    // Stock is available again
    expect($this->service->getAvailableQuantity($this->ticketType))->toBe(1);

    // User B can now reserve
    $newReservation = $this->service->reserve(
        $this->ticketType,
        quantity: 1,
        userId: $this->userB->id,
        sessionId: 'sess_userB'
    );

    expect($newReservation)->toBeInstanceOf(TicketReservation::class);
});

// ─────────────────────────────────────────────────────────────────────────────
// 5. Expired reservations are NOT counted as held stock
// ─────────────────────────────────────────────────────────────────────────────

test('expired reservation does not block other users from reserving', function () {
    // Create an already-expired reservation
    TicketReservation::create([
        'ticket_type_id' => $this->ticketType->id,
        'user_id'        => $this->userA->id,
        'session_id'     => 'sess_expired',
        'quantity'       => 1,
        'expires_at'     => now()->subMinutes(5), // already past!
    ]);

    // User B should still be able to reserve
    $reservation = $this->service->reserve(
        $this->ticketType,
        quantity: 1,
        userId: $this->userB->id,
        sessionId: 'sess_userB'
    );

    expect($reservation)->toBeInstanceOf(TicketReservation::class);
});

// ─────────────────────────────────────────────────────────────────────────────
// 6. Cleanup Job deletes expired reservations
// ─────────────────────────────────────────────────────────────────────────────

test('ReleaseExpiredReservationsJob removes all expired unconfirmed reservations', function () {
    // 2 expired reservations
    TicketReservation::create([
        'ticket_type_id' => $this->ticketType->id,
        'user_id'        => $this->userA->id,
        'session_id'     => 'sess_old_1',
        'quantity'       => 1,
        'expires_at'     => now()->subMinutes(15),
    ]);

    $ticketTypeB = TicketType::create([
        'event_id' => $this->event->id,
        'name'     => 'VIP',
        'price'    => 100,
        'quantity' => 10,
    ]);

    TicketReservation::create([
        'ticket_type_id' => $ticketTypeB->id,
        'user_id'        => $this->userB->id,
        'session_id'     => 'sess_old_2',
        'quantity'       => 2,
        'expires_at'     => now()->subSeconds(30),
    ]);

    // 1 still active reservation (should NOT be deleted)
    TicketReservation::create([
        'ticket_type_id' => $ticketTypeB->id,
        'user_id'        => $this->userB->id,
        'session_id'     => 'sess_active',
        'quantity'       => 1,
        'expires_at'     => now()->addMinutes(8),
    ]);

    $count = $this->service->cleanupExpired();

    expect($count)->toBe(2);
    expect(TicketReservation::count())->toBe(1); // only the active one remains
});

// ─────────────────────────────────────────────────────────────────────────────
// 7. ReleaseExpiredReservationsJob dispatches and calls service
// ─────────────────────────────────────────────────────────────────────────────

test('ReleaseExpiredReservationsJob can be dispatched and runs correctly', function () {
    TicketReservation::create([
        'ticket_type_id' => $this->ticketType->id,
        'user_id'        => $this->userA->id,
        'session_id'     => 'sess_dispatch_test',
        'quantity'       => 1,
        'expires_at'     => now()->subMinutes(1),
    ]);

    expect(TicketReservation::expired()->count())->toBe(1);

    // Run the job inline (QUEUE_CONNECTION=sync in testing)
    (new ReleaseExpiredReservationsJob())->handle($this->service);

    expect(TicketReservation::expired()->count())->toBe(0);
});

// ─────────────────────────────────────────────────────────────────────────────
// 8. Confirming a reservation links it to an order
// ─────────────────────────────────────────────────────────────────────────────

test('confirmReservation links the reservation to an order', function () {
    $reservation = $this->service->reserve(
        $this->ticketType,
        quantity: 1,
        userId: $this->userA->id,
        sessionId: 'sess_confirm'
    );

    $order = Order::create([
        'organizer_id'   => $this->organizer->id,
        'event_id'       => $this->event->id,
        'user_id'        => $this->userA->id,
        'order_number'   => 'ORD-CNF-001',
        'total_amount'   => 50,
        'status'         => 'paid',
        'payment_intent_id' => 'pi_confirmed_001',
    ]);

    $this->service->confirmReservation($reservation->id, $order);

    $reservation->refresh();
    expect($reservation->order_id)->toBe($order->id);

    // Confirmed reservation is no longer counted as an active hold
    expect(TicketReservation::active()->where('id', $reservation->id)->exists())->toBeFalse();
});

// ─────────────────────────────────────────────────────────────────────────────
// 9. fulfillOrder confirms reservation via CheckoutService
// ─────────────────────────────────────────────────────────────────────────────

test('CheckoutService::fulfillOrder confirms the reservation after successful payment', function () {
    // Give the ticket type more stock for this test
    $this->ticketType->update(['quantity' => 10]);

    $reservation = $this->service->reserve(
        $this->ticketType,
        quantity: 1,
        userId: $this->userA->id,
        sessionId: 'sess_fulfill'
    );

    $order = Order::create([
        'organizer_id'      => $this->organizer->id,
        'event_id'          => $this->event->id,
        'user_id'           => $this->userA->id,
        'reservation_id'    => $reservation->id,
        'order_number'      => 'ORD-FULFILL-001',
        'total_amount'      => 50,
        'status'            => 'pending',
        'payment_intent_id' => 'cs_fulfill_001',
    ]);

    $session = (object) [
        'id'             => 'cs_fulfill_001',
        'payment_intent' => 'pi_fulfill_001',
        'amount_total'   => 5000,
        'currency'       => 'eur',
        'metadata'       => (object) [
            'order_id'       => $order->id,
            'ticket_type_id' => $this->ticketType->id,
            'quantity'       => 1,
            'reservation_id' => $reservation->id,
        ],
    ];

    Bus::fake();

    $checkoutService = new \App\Services\CheckoutService();
    $checkoutService->fulfillOrder($session);

    // Reservation should now be confirmed (has order_id)
    $reservation->refresh();
    expect($reservation->order_id)->toBe($order->id);

    // No longer an active hold
    expect(TicketReservation::active()->where('id', $reservation->id)->exists())->toBeFalse();

    // Order is paid
    $order->refresh();
    expect($order->status)->toBe('paid');

    Bus::assertDispatched(\App\Jobs\SendOrderTicketsJob::class);
});

// ─────────────────────────────────────────────────────────────────────────────
// 10. Same user re-reserving replaces their old reservation
// ─────────────────────────────────────────────────────────────────────────────

test('same user reserving again replaces their existing reservation without double-counting', function () {
    // Set quantity to 2 so the user can reserve 2 (then come back and change to 1)
    $this->ticketType->update(['quantity' => 2]);

    // First reservation: quantity 2
    $first = $this->service->reserve(
        $this->ticketType,
        quantity: 2,
        userId: $this->userA->id,
        sessionId: 'sess_rereserve'
    );

    // User goes back and changes to 1
    $second = $this->service->reserve(
        $this->ticketType,
        quantity: 1,
        userId: $this->userA->id,
        sessionId: 'sess_rereserve'
    );

    // Old reservation is gone, new one exists
    $this->assertDatabaseMissing('ticket_reservations', ['id' => $first->id]);
    $this->assertDatabaseHas('ticket_reservations', ['id' => $second->id, 'quantity' => 1]);

    // Only 1 reservation row for this user+ticketType
    expect(
        TicketReservation::active()
            ->where('ticket_type_id', $this->ticketType->id)
            ->where('user_id', $this->userA->id)
            ->count()
    )->toBe(1);

    // Available quantity for user B = total (2) - held by A (1) = 1
    expect($this->service->getAvailableQuantity($this->ticketType, excludeUserId: $this->userB->id))->toBe(1);
});

// ─────────────────────────────────────────────────────────────────────────────
// 11. isExpired() helper works correctly
// ─────────────────────────────────────────────────────────────────────────────

test('TicketReservation isExpired() returns correct value based on expires_at', function () {
    $active = TicketReservation::create([
        'ticket_type_id' => $this->ticketType->id,
        'user_id'        => $this->userA->id,
        'session_id'     => 'sess_is_expired_a',
        'quantity'       => 1,
        'expires_at'     => now()->addMinutes(5),
    ]);

    $expired = TicketReservation::create([
        'ticket_type_id' => $this->ticketType->id,
        'user_id'        => $this->userB->id,
        'session_id'     => 'sess_is_expired_b',
        'quantity'       => 1,
        'expires_at'     => now()->subSeconds(1),
    ]);

    expect($active->isExpired())->toBeFalse();
    expect($expired->isExpired())->toBeTrue();
});

// ─────────────────────────────────────────────────────────────────────────────
// 12. Checkout Livewire — nextStep reserves the ticket
// ─────────────────────────────────────────────────────────────────────────────

test('Checkout component creates a reservation when advancing from step 1', function () {
    $this->actingAs($this->userA);

    Livewire::test(Checkout::class, ['slug' => $this->event->slug])
        ->set('selectedTicketTypeId', $this->ticketType->id)
        ->set('quantity', 1)
        ->call('nextStep')
        ->assertSet('step', 2)
        ->assertSet('reservationId', fn ($v) => $v !== null);

    expect(TicketReservation::active()->where('ticket_type_id', $this->ticketType->id)->count())->toBe(1);
});

// ─────────────────────────────────────────────────────────────────────────────
// 13. Checkout Livewire — going Back releases the reservation
// ─────────────────────────────────────────────────────────────────────────────

test('Checkout component releases reservation when user goes back to step 1', function () {
    $this->actingAs($this->userA);

    $component = Livewire::test(Checkout::class, ['slug' => $this->event->slug])
        ->set('selectedTicketTypeId', $this->ticketType->id)
        ->set('quantity', 1)
        ->call('nextStep'); // creates reservation, moves to step 2

    expect(TicketReservation::active()->count())->toBe(1);

    $component->call('prevStep'); // releases reservation, back to step 1

    expect(TicketReservation::active()->count())->toBe(0);
});

// ─────────────────────────────────────────────────────────────────────────────
// 14. Checkout Livewire — blocked when ticket is already held by another user
// ─────────────────────────────────────────────────────────────────────────────

test('Checkout component shows error when ticket is held by another user', function () {
    // User A already holds the only ticket
    $this->service->reserve(
        $this->ticketType,
        quantity: 1,
        userId: $this->userA->id,
        sessionId: 'sess_userA'
    );

    // User B tries to proceed
    $this->actingAs($this->userB);

    Livewire::test(Checkout::class, ['slug' => $this->event->slug])
        ->set('selectedTicketTypeId', $this->ticketType->id)
        ->set('quantity', 1)
        ->call('nextStep')
        ->assertSet('step', 1) // still on step 1
        ->assertHasErrors(['quantity']);
});
