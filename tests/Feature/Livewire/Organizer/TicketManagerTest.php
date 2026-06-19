<?php

use App\Enums\EventStatus;
use App\Enums\Role;
use App\Livewire\Organizer\TicketManager;
use App\Models\Event;
use App\Models\Organizer;
use App\Models\TicketType;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->user = User::factory()->create(['role' => Role::ORGANIZER]);
    $this->organizer = Organizer::create([
        'user_id' => $this->user->id,
        'company_name' => 'Test Company',
        'slug' => 'test-company',
    ]);

    $this->event = Event::create([
        'organizer_id' => $this->organizer->id,
        'title' => 'Test Event',
        'slug' => 'test-event',
        'category' => 'Music',
        'city' => 'Brussels',
        'country' => 'Belgium',
        'start_date' => now()->addDays(5),
        'capacity' => 100,
        'status' => EventStatus::PUBLISHED,
    ]);
});

test('organizer can see their event ticket types', function () {
    $ticketType = TicketType::create([
        'event_id' => $this->event->id,
        'name' => 'General Admission',
        'price' => 50,
        'quantity' => 100,
        'max_per_order' => 5,
    ]);

    Livewire::actingAs($this->user)
        ->test(TicketManager::class, ['event' => $this->event])
        ->assertSee('General Admission');
});

test('organizer cannot access other organizer event ticket manager', function () {
    $otherUser = User::factory()->create(['role' => Role::ORGANIZER]);
    $otherOrganizer = Organizer::create([
        'user_id' => $otherUser->id,
        'company_name' => 'Other Company',
        'slug' => 'other-company',
    ]);

    Livewire::actingAs($otherUser)
        ->test(TicketManager::class, ['event' => $this->event])
        ->assertForbidden();
});

test('organizer can create a ticket type', function () {
    Livewire::actingAs($this->user)
        ->test(TicketManager::class, ['event' => $this->event])
        ->set('name', 'VIP Pass')
        ->set('price', 150)
        ->set('quantity', 20)
        ->set('max_per_order', 2)
        ->call('saveTicket');

    expect(TicketType::where('name', 'VIP Pass')->exists())->toBeTrue();
});

test('organizer can edit a ticket type', function () {
    $ticketType = TicketType::create([
        'event_id' => $this->event->id,
        'name' => 'Early Bird',
        'price' => 30,
        'quantity' => 50,
        'max_per_order' => 5,
    ]);

    Livewire::actingAs($this->user)
        ->test(TicketManager::class, ['event' => $this->event])
        ->call('editTicket', $ticketType->id)
        ->set('price', 35)
        ->call('saveTicket');

    expect($ticketType->refresh()->price)->toEqual(35);
});

test('organizer can delete a ticket type', function () {
    $ticketType = TicketType::create([
        'event_id' => $this->event->id,
        'name' => 'Student',
        'price' => 20,
        'quantity' => 30,
        'max_per_order' => 2,
    ]);

    Livewire::actingAs($this->user)
        ->test(TicketManager::class, ['event' => $this->event])
        ->call('deleteTicket', $ticketType->id);

    expect(TicketType::where('id', $ticketType->id)->exists())->toBeFalse();
});
