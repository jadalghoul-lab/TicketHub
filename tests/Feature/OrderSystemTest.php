<?php

use App\Enums\EventStatus;
use App\Enums\Role;
use App\Jobs\SendOrderTicketsJob;
use App\Models\Event;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Organizer;
use App\Models\Ticket;
use App\Models\TicketType;
use App\Models\User;
use App\Services\CheckoutService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Str;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->user = User::factory()->create(['role' => Role::CUSTOMER]);
    $this->organizerUser = User::factory()->create(['role' => Role::ORGANIZER]);
    $this->organizer = Organizer::create([
        'user_id' => $this->organizerUser->id,
        'company_name' => 'Test Org',
        'slug' => 'test-org',
    ]);

    $this->event = Event::create([
        'organizer_id' => $this->organizer->id,
        'title' => 'Test Event',
        'slug' => 'test-event',
        'category' => 'music',
        'city' => 'Brussels',
        'country' => 'Belgium',
        'start_date' => now()->addDays(10),
        'status' => EventStatus::PUBLISHED,
    ]);

    $this->ticketType = TicketType::create([
        'event_id' => $this->event->id,
        'name' => 'General',
        'price' => 50,
        'quantity' => 100,
    ]);
});

test('order system fulfills order and creates all required records', function () {
    $order = Order::create([
        'organizer_id' => $this->organizer->id,
        'event_id' => $this->event->id,
        'user_id' => $this->user->id,
        'order_number' => 'ORD-TEST',
        'total_amount' => 50,
        'status' => 'pending',
        'payment_intent_id' => 'cs_test_123',
    ]);

    OrderItem::create([
        'order_id' => $order->id,
        'ticket_type_id' => $this->ticketType->id,
        'quantity' => 1,
        'unit_price' => 50,
        'subtotal' => 50,
    ]);

    // 2. Simulate Webhook
    $session = (object) [
        'id' => 'cs_test_123',
        'payment_intent' => 'pi_test_123',
        'amount_total' => 5000, // 50.00
        'currency' => 'eur',
        'metadata' => (object) [
            'order_id' => $order->id,
            'ticket_type_id' => $this->ticketType->id,
            'quantity' => 1,
        ],
    ];

    Bus::fake();

    $service = new CheckoutService;
    $service->fulfillOrder($session);

    // 3. Assertions
    Bus::assertDispatched(SendOrderTicketsJob::class);

    $order->refresh();
    expect($order->status)->toBe('paid');

    $this->assertDatabaseHas('payments', [
        'order_id' => $order->id,
        'stripe_payment_id' => 'pi_test_123',
        'amount' => 50.00,
        'status' => 'succeeded',
    ]);

    $this->assertDatabaseHas('tickets', [
        'order_id' => $order->id,
        'user_id' => $this->user->id,
        'ticket_type_id' => $this->ticketType->id,
        'status' => 'valid',
    ]);

    expect(Ticket::where('order_id', $order->id)->count())->toBe(1);

    $this->ticketType->refresh();
    expect($this->ticketType->quantity)->toBe(99);
});

test('webhook handles payment failure', function () {
    $order = Order::create([
        'organizer_id' => $this->organizer->id,
        'event_id' => $this->event->id,
        'user_id' => $this->user->id,
        'order_number' => 'ORD-FAIL',
        'total_amount' => 50,
        'status' => 'pending',
        'payment_intent_id' => 'pi_fail_123',
    ]);

    $intent = (object) [
        'id' => 'pi_fail_123',
    ];

    $service = new CheckoutService;
    $service->handleFailure($intent);

    $order->refresh();
    expect($order->status)->toBe('failed');
});

test('webhook handles charge refund', function () {
    $order = Order::create([
        'organizer_id' => $this->organizer->id,
        'event_id' => $this->event->id,
        'user_id' => $this->user->id,
        'order_number' => 'ORD-REFUND',
        'total_amount' => 50,
        'status' => 'paid',
        'payment_intent_id' => 'pi_refund_123',
    ]);

    Ticket::create([
        'order_id' => $order->id,
        'event_id' => $this->event->id,
        'user_id' => $this->user->id,
        'ticket_type_id' => $this->ticketType->id,
        'uuid' => (string) Str::uuid(),
        'ticket_number' => 'TKT-REF',
        'status' => 'valid',
    ]);

    $charge = (object) [
        'payment_intent' => 'pi_refund_123',
    ];

    $service = new CheckoutService;
    $service->handleRefund($charge);

    $order->refresh();
    expect($order->status)->toBe('refunded');

    expect(Ticket::where('order_id', $order->id)->first()->status)->toBe('refunded');
});
