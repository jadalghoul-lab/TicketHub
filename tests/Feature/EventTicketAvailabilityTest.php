<?php

/**
 * ─────────────────────────────────────────────────────────────────────────────
 * Event Show Page — Live Ticket Availability Pest Tests
 *
 * Covers the ticketAvailability data passed by EventController::show() and
 * the corresponding UI states rendered in public/events/show.blade.php:
 *
 *  1.  Page passes $ticketAvailability to the view
 *  2.  Available ticket: shows green "X available" badge
 *  3.  Available ticket: Buy Now button is enabled
 *  4.  Low stock (≤5): shows "Only X left!" badge
 *  5.  Temporarily held (all held, none available): shows "Temporarily Held"
 *  6.  Temporarily held: Buy Now button is DISABLED / shows "In Checkout"
 *  7.  Temporarily held: global "Being purchased right now" notice shown
 *  8.  Sold out (quantity=0): shows "Sold Out" badge
 *  9.  Sold out: Buy Now button is DISABLED / shows "Sold Out"
 * 10.  Mixed: one type held + another type available → Buy Now still visible
 * 11.  Expired reservation does NOT affect display (ticket shown as available)
 * 12.  Confirmed reservation (order_id set) does NOT block display
 * 13.  ticketAvailability keys match ticket type IDs
 * 14.  is_low flag only triggers at ≤5 remaining
 * ─────────────────────────────────────────────────────────────────────────────
 */

use App\Enums\EventStatus;
use App\Enums\Role;
use App\Models\Event;
use App\Models\Order;
use App\Models\Organizer;
use App\Models\TicketReservation;
use App\Models\TicketType;
use App\Models\User;
use App\Services\TicketReservationService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;

uses(RefreshDatabase::class);

// ─── Shared Setup ────────────────────────────────────────────────────────────

beforeEach(function () {
    $organizerUser = User::factory()->create(['role' => Role::ORGANIZER]);
    $this->organizer = Organizer::create([
        'user_id' => $organizerUser->id,
        'company_name' => 'Live Org',
        'slug' => 'live-org-'.Str::random(4),
    ]);

    $this->event = Event::create([
        'organizer_id' => $this->organizer->id,
        'title' => 'Live Availability Event',
        'slug' => 'live-avail-'.Str::random(4),
        'category' => 'music',
        'city' => 'Brussels',
        'country' => 'Belgium',
        'start_date' => now()->addDays(10),
        'status' => EventStatus::PUBLISHED,
    ]);

    $this->customer = User::factory()->create(['role' => Role::CUSTOMER]);
    $this->customerB = User::factory()->create(['role' => Role::CUSTOMER]);

    $this->service = app(TicketReservationService::class);
});

// ─── Helper: create active reservation ───────────────────────────────────────

function holdTicket(TicketType $tt, User $user, int $qty = 1): TicketReservation
{
    return TicketReservation::create([
        'ticket_type_id' => $tt->id,
        'user_id' => $user->id,
        'session_id' => 'sess_'.Str::random(6),
        'quantity' => $qty,
        'expires_at' => now()->addMinutes(10),
    ]);
}

// ─────────────────────────────────────────────────────────────────────────────
// 1. Controller passes $ticketAvailability to the view
// ─────────────────────────────────────────────────────────────────────────────

test('event show page passes ticketAvailability variable to the view', function () {
    TicketType::create([
        'event_id' => $this->event->id,
        'name' => 'General',
        'price' => 50,
        'quantity' => 10,
    ]);

    $this->actingAs($this->customer)
        ->get(route('public.events.show', $this->event->slug))
        ->assertStatus(200)
        ->assertViewHas('ticketAvailability');
});

// ─────────────────────────────────────────────────────────────────────────────
// 2. Available ticket shows green "X available" badge
// ─────────────────────────────────────────────────────────────────────────────

test('available ticket shows correct available count badge on event page', function () {
    TicketType::create([
        'event_id' => $this->event->id,
        'name' => 'General',
        'price' => 50,
        'quantity' => 20,
    ]);

    $this->actingAs($this->customer)
        ->get(route('public.events.show', $this->event->slug))
        ->assertStatus(200)
        ->assertSee('20 available');
});

// ─────────────────────────────────────────────────────────────────────────────
// 3. Available ticket: Buy Now button is present and enabled
// ─────────────────────────────────────────────────────────────────────────────

test('Buy Now link is visible when tickets are available', function () {
    TicketType::create([
        'event_id' => $this->event->id,
        'name' => 'General',
        'price' => 50,
        'quantity' => 5,
    ]);

    $this->actingAs($this->customer)
        ->get(route('public.events.show', $this->event->slug))
        ->assertStatus(200)
        ->assertSee('Buy Now')
        ->assertSee(route('public.checkout', $this->event->slug));
});

// ─────────────────────────────────────────────────────────────────────────────
// 4. Low stock (≤5): shows "Only X left!" badge
// ─────────────────────────────────────────────────────────────────────────────

test('low stock ticket shows "Only X left!" warning badge', function () {
    TicketType::create([
        'event_id' => $this->event->id,
        'name' => 'VIP',
        'price' => 100,
        'quantity' => 3,
    ]);

    $this->actingAs($this->customer)
        ->get(route('public.events.show', $this->event->slug))
        ->assertStatus(200)
        ->assertSee('Only 3 left!');
});

// ─────────────────────────────────────────────────────────────────────────────
// 5. Temporarily held: shows "Temporarily Held" badge
// ─────────────────────────────────────────────────────────────────────────────

test('held ticket shows "Temporarily Held" badge on event page', function () {
    $tt = TicketType::create([
        'event_id' => $this->event->id,
        'name' => 'General',
        'price' => 50,
        'quantity' => 1,
    ]);

    holdTicket($tt, $this->customerB); // customer B holds the only ticket

    $this->actingAs($this->customer) // customer A views the page
        ->get(route('public.events.show', $this->event->slug))
        ->assertStatus(200)
        ->assertSee('Temporarily Held');
});

// ─────────────────────────────────────────────────────────────────────────────
// 6. Temporarily held: Buy Now button is disabled / shows "In Checkout"
// ─────────────────────────────────────────────────────────────────────────────

test('when all tickets are held the Buy Now button is replaced with In Checkout notice', function () {
    $tt = TicketType::create([
        'event_id' => $this->event->id,
        'name' => 'General',
        'price' => 50,
        'quantity' => 1,
    ]);

    holdTicket($tt, $this->customerB);

    $response = $this->actingAs($this->customer)
        ->get(route('public.events.show', $this->event->slug));

    $response->assertStatus(200)
        ->assertSee('In Checkout')
        ->assertDontSee(route('public.checkout', $this->event->slug)); // link gone
});

// ─────────────────────────────────────────────────────────────────────────────
// 7. Temporarily held: global "Being purchased right now" notice shown
// ─────────────────────────────────────────────────────────────────────────────

test('global "Being purchased right now" notice appears when all tickets are held', function () {
    $tt = TicketType::create([
        'event_id' => $this->event->id,
        'name' => 'General',
        'price' => 50,
        'quantity' => 1,
    ]);

    holdTicket($tt, $this->customerB);

    $this->actingAs($this->customer)
        ->get(route('public.events.show', $this->event->slug))
        ->assertStatus(200)
        ->assertSee('Being purchased right now');
});

// ─────────────────────────────────────────────────────────────────────────────
// 8. Sold out (raw quantity = 0): shows "Sold Out" badge
// ─────────────────────────────────────────────────────────────────────────────

test('sold out ticket shows "Sold Out" badge on event page', function () {
    TicketType::create([
        'event_id' => $this->event->id,
        'name' => 'General',
        'price' => 50,
        'quantity' => 0,
    ]);

    $this->actingAs($this->customer)
        ->get(route('public.events.show', $this->event->slug))
        ->assertStatus(200)
        ->assertSee('Sold Out');
});

// ─────────────────────────────────────────────────────────────────────────────
// 9. Sold out: Buy Now is disabled and shows "Sold Out" button text
// ─────────────────────────────────────────────────────────────────────────────

test('sold out event does not show Buy Now link', function () {
    TicketType::create([
        'event_id' => $this->event->id,
        'name' => 'General',
        'price' => 50,
        'quantity' => 0,
    ]);

    $this->actingAs($this->customer)
        ->get(route('public.events.show', $this->event->slug))
        ->assertStatus(200)
        ->assertDontSee(route('public.checkout', $this->event->slug));
});

// ─────────────────────────────────────────────────────────────────────────────
// 10. Mixed: one held + one available → Buy Now still visible
// ─────────────────────────────────────────────────────────────────────────────

test('Buy Now is still available when one ticket type is held but another is available', function () {
    $general = TicketType::create([
        'event_id' => $this->event->id,
        'name' => 'General',
        'price' => 30,
        'quantity' => 1,
    ]);

    $vip = TicketType::create([
        'event_id' => $this->event->id,
        'name' => 'VIP',
        'price' => 100,
        'quantity' => 10,
    ]);

    holdTicket($general, $this->customerB); // General is held

    $response = $this->actingAs($this->customer)
        ->get(route('public.events.show', $this->event->slug));

    $response->assertStatus(200)
        ->assertSee('Temporarily Held')  // General shows held
        ->assertSee('Buy Now')            // VIP is still available
        ->assertSee(route('public.checkout', $this->event->slug));
});

// ─────────────────────────────────────────────────────────────────────────────
// 11. Expired reservation does NOT block display (shows available)
// ─────────────────────────────────────────────────────────────────────────────

test('expired reservation does not show Temporarily Held on event page', function () {
    $tt = TicketType::create([
        'event_id' => $this->event->id,
        'name' => 'General',
        'price' => 50,
        'quantity' => 1,
    ]);

    // Create an EXPIRED hold
    TicketReservation::create([
        'ticket_type_id' => $tt->id,
        'user_id' => $this->customerB->id,
        'session_id' => 'sess_expired',
        'quantity' => 1,
        'expires_at' => now()->subMinutes(5), // past!
    ]);

    $response = $this->actingAs($this->customer)
        ->get(route('public.events.show', $this->event->slug))
        ->assertStatus(200);

    // qty=1 triggers is_low (≤5) so view shows "Only 1 left!" — NOT "Temporarily Held"
    $response->assertSee('Only 1 left!');
    $response->assertDontSee('Temporarily Held'); // no false alarm from expired hold
});

// ─────────────────────────────────────────────────────────────────────────────
// 12. Confirmed reservation (order_id set) does NOT block display
// ─────────────────────────────────────────────────────────────────────────────

test('confirmed reservation with order_id does not block ticket availability display', function () {
    $tt = TicketType::create([
        'event_id' => $this->event->id,
        'name' => 'General',
        'price' => 50,
        'quantity' => 20, // use >5 so we avoid is_low badge
    ]);

    $order = Order::create([
        'organizer_id' => $this->organizer->id,
        'event_id' => $this->event->id,
        'user_id' => $this->customerB->id,
        'order_number' => 'ORD-CONF-TEST',
        'total_amount' => 50,
        'status' => 'paid',
        'payment_intent_id' => 'pi_confirmed',
    ]);

    // Confirmed reservation — has order_id, so NOT counted as an active hold
    TicketReservation::create([
        'ticket_type_id' => $tt->id,
        'user_id' => $this->customerB->id,
        'session_id' => 'sess_confirmed',
        'quantity' => 1,
        'expires_at' => now()->addMinutes(5),
        'order_id' => $order->id, // confirmed!
    ]);

    $response = $this->actingAs($this->customer)
        ->get(route('public.events.show', $this->event->slug))
        ->assertStatus(200);

    // 20 tickets available, confirmed reservation should NOT subtract from display
    $response->assertSee('20 available');
    $response->assertDontSee('Temporarily Held');
});

// ─────────────────────────────────────────────────────────────────────────────
// 13. ticketAvailability keys match ticket type IDs exactly
// ─────────────────────────────────────────────────────────────────────────────

test('ticketAvailability array is keyed by ticket type ID', function () {
    $tt1 = TicketType::create(['event_id' => $this->event->id, 'name' => 'General', 'price' => 30, 'quantity' => 10]);
    $tt2 = TicketType::create(['event_id' => $this->event->id, 'name' => 'VIP',     'price' => 80, 'quantity' => 5]);

    $response = $this->actingAs($this->customer)
        ->get(route('public.events.show', $this->event->slug));

    $availability = $response->viewData('ticketAvailability');

    expect($availability)->toHaveKey($tt1->id);
    expect($availability)->toHaveKey($tt2->id);
    expect($availability[$tt1->id]['available'])->toBe(10);
    expect($availability[$tt2->id]['available'])->toBe(5);
});

// ─────────────────────────────────────────────────────────────────────────────
// 14. is_low triggers only at ≤5, not above
// ─────────────────────────────────────────────────────────────────────────────

test('is_low flag is true only when available quantity is 5 or fewer', function () {
    $ttLow = TicketType::create(['event_id' => $this->event->id, 'name' => 'Low',  'price' => 50, 'quantity' => 5]);
    $ttHigh = TicketType::create(['event_id' => $this->event->id, 'name' => 'High', 'price' => 50, 'quantity' => 6]);

    $response = $this->actingAs($this->customer)
        ->get(route('public.events.show', $this->event->slug));

    $availability = $response->viewData('ticketAvailability');

    expect($availability[$ttLow->id]['is_low'])->toBeTrue();
    expect($availability[$ttHigh->id]['is_low'])->toBeFalse();
});
