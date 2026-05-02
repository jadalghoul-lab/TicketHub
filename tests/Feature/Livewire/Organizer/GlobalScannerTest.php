<?php

use App\Livewire\Organizer\GlobalScanner;
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
    
    // Multiple events
    $this->event1 = Event::factory()->create(['organizer_id' => $this->organizer->id, 'title' => 'Event One']);
    $this->event2 = Event::factory()->create(['organizer_id' => $this->organizer->id, 'title' => 'Event Two']);
    
    $this->type1 = TicketType::factory()->create(['event_id' => $this->event1->id]);
    $this->type2 = TicketType::factory()->create(['event_id' => $this->event2->id]);
});

test('global scanner can validate tickets from multiple events', function () {
    $ticket1 = Ticket::factory()->create([
        'event_id' => $this->event1->id,
        'ticket_type_id' => $this->type1->id,
        'status' => 'valid',
    ]);

    $ticket2 = Ticket::factory()->create([
        'event_id' => $this->event2->id,
        'ticket_type_id' => $this->type2->id,
        'status' => 'valid',
    ]);

    $component = Livewire::actingAs($this->user)
        ->test(GlobalScanner::class);

    // Scan ticket from event 1
    $component->set('manualCode', $ticket1->uuid)
        ->call('scan')
        ->assertDispatched('scan-success')
        ->assertSee('Event One');

    // Scan ticket from event 2
    $component->set('manualCode', $ticket2->uuid)
        ->call('scan')
        ->assertDispatched('scan-success')
        ->assertSee('Event Two');

    expect($ticket1->refresh()->status)->toEqual('used');
    expect($ticket2->refresh()->status)->toEqual('used');
});

test('global scanner does not validate tickets from other organizers', function () {
    $otherUser = User::factory()->create(['role' => Role::ORGANIZER]);
    $otherOrganizer = Organizer::factory()->create(['user_id' => $otherUser->id]);
    $otherEvent = Event::factory()->create(['organizer_id' => $otherOrganizer->id]);
    $otherType = TicketType::factory()->create(['event_id' => $otherEvent->id]);
    
    $otherTicket = Ticket::factory()->create([
        'event_id' => $otherEvent->id,
        'ticket_type_id' => $otherType->id,
        'status' => 'valid',
    ]);

    Livewire::actingAs($this->user)
        ->test(GlobalScanner::class)
        ->set('manualCode', $otherTicket->uuid)
        ->call('scan')
        ->assertDispatched('scan-error');

    expect($otherTicket->refresh()->status)->toEqual('valid');
});
