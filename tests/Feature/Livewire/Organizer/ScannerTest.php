<?php

use App\Livewire\Organizer\Scanner;
use App\Models\Event;
use App\Models\Organizer;
use App\Models\Ticket;
use App\Models\TicketType;
use App\Models\User;
use App\Enums\Role;
use Livewire\Livewire;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->user = User::factory()->create(['role' => Role::ORGANIZER]);
    $this->organizer = Organizer::factory()->create(['user_id' => $this->user->id]);
    $this->event = Event::factory()->create(['organizer_id' => $this->organizer->id]);
    $this->ticketType = TicketType::factory()->create(['event_id' => $this->event->id]);
});

test('organizer can access scanner for their event', function () {
    Livewire::actingAs($this->user)
        ->test(Scanner::class, ['event' => $this->event])
        ->assertStatus(200);
});

test('organizer cannot access scanner for other organizer event', function () {
    $otherUser = User::factory()->create(['role' => Role::ORGANIZER]);
    $otherOrganizer = Organizer::factory()->create(['user_id' => $otherUser->id]);
    $otherEvent = Event::factory()->create(['organizer_id' => $otherOrganizer->id]);

    Livewire::actingAs($this->user)
        ->test(Scanner::class, ['event' => $otherEvent])
        ->assertStatus(403);
});

test('scanner can validate and check in a valid ticket', function () {
    $customer = User::factory()->create();
    $ticket = Ticket::factory()->create([
        'event_id' => $this->event->id,
        'ticket_type_id' => $this->ticketType->id,
        'user_id' => $customer->id,
        'status' => 'valid',
    ]);

    Livewire::actingAs($this->user)
        ->test(Scanner::class, ['event' => $this->event])
        ->set('manualCode', $ticket->uuid)
        ->call('scan')
        ->assertHasNoErrors()
        ->assertDispatched('scan-success');

    expect($ticket->refresh()->status)->toEqual('used');
    expect($ticket->scanned_at)->not->toBeNull();
});

test('scanner handles invalid ticket code', function () {
    Livewire::actingAs($this->user)
        ->test(Scanner::class, ['event' => $this->event])
        ->set('manualCode', 'INVALID-CODE')
        ->call('scan')
        ->assertDispatched('scan-error');
});

test('scanner handles already used ticket', function () {
    $customer = User::factory()->create();
    $ticket = Ticket::factory()->create([
        'event_id' => $this->event->id,
        'ticket_type_id' => $this->ticketType->id,
        'user_id' => $customer->id,
        'status' => 'used',
        'scanned_at' => now(),
    ]);

    Livewire::actingAs($this->user)
        ->test(Scanner::class, ['event' => $this->event])
        ->set('manualCode', $ticket->uuid)
        ->call('scan')
        ->assertDispatched('scan-error');
});
