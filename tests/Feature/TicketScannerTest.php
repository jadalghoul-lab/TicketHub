<?php

use App\Models\User;
use App\Models\Event;
use App\Models\Organizer;
use App\Models\TicketType;
use App\Models\Ticket;
use App\Models\Order;
use App\Enums\Role;
use App\Enums\EventStatus;
use App\Services\TicketService;
use Illuminate\Support\Str;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

beforeEach(function () {
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

    $this->customer = User::factory()->create(['role' => Role::CUSTOMER]);
    
    $this->order = Order::create([
        'organizer_id' => $this->organizer->id,
        'event_id' => $this->event->id,
        'user_id' => $this->customer->id,
        'order_number' => 'ORD-123',
        'total_amount' => 50,
        'status' => 'paid',
    ]);

    $this->ticket = Ticket::create([
        'order_id' => $this->order->id,
        'event_id' => $this->event->id,
        'user_id' => $this->customer->id,
        'ticket_type_id' => $this->ticketType->id,
        'uuid' => (string) Str::uuid(),
        'ticket_number' => 'TKT-SCAN-TEST',
        'status' => 'valid',
    ]);
});

test('organizer can validate and check-in a valid ticket', function () {
    $service = new TicketService();
    $result = $service->validateAndCheckIn($this->ticket->uuid, $this->event->id, $this->organizerUser->id);

    expect($result['success'])->toBeTrue();
    expect($result['message'])->toBe('Access Granted!');
    
    $this->ticket->refresh();
    expect($this->ticket->status)->toBe('used');
    expect($this->ticket->scanned_at)->not->toBeNull();
    expect($this->ticket->scanned_by)->toBe($this->organizerUser->id);
});

test('scanner denies used tickets', function () {
    $this->ticket->update(['status' => 'used', 'scanned_at' => now()]);

    $service = new TicketService();
    $result = $service->validateAndCheckIn($this->ticket->uuid, $this->event->id, $this->organizerUser->id);

    expect($result['success'])->toBeFalse();
    expect($result['message'])->toContain('Ticket already used');
});

test('scanner denies tickets from other events', function () {
    $otherEvent = Event::create([
        'organizer_id' => $this->organizer->id,
        'title' => 'Other Event',
        'slug' => 'other-event',
        'category' => 'music',
        'city' => 'Ghent',
        'country' => 'Belgium',
        'start_date' => now()->addDays(5),
        'status' => EventStatus::PUBLISHED,
    ]);

    $service = new TicketService();
    $result = $service->validateAndCheckIn($this->ticket->uuid, $otherEvent->id, $this->organizerUser->id);

    expect($result['success'])->toBeFalse();
    expect($result['message'])->toBe('Invalid ticket code.');
});

test('scanner denies invalid codes', function () {
    $service = new TicketService();
    $result = $service->validateAndCheckIn('INVALID-CODE', $this->event->id, $this->organizerUser->id);

    expect($result['success'])->toBeFalse();
    expect($result['message'])->toBe('Invalid ticket code.');
});
