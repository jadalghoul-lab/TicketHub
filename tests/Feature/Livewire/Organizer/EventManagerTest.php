<?php

use App\Enums\EventStatus;
use App\Enums\Role;
use App\Livewire\Organizer\EventManager;
use App\Models\Event;
use App\Models\Organizer;
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
});

test('organizer can see their events', function () {
    $event = Event::create([
        'organizer_id' => $this->organizer->id,
        'title' => 'Test Event',
        'slug' => 'test-event-1',
        'category' => 'Music',
        'city' => 'Brussels',
        'country' => 'Belgium',
        'start_date' => now()->addDays(5),
        'capacity' => 100,
        'status' => EventStatus::PUBLISHED,
    ]);

    Livewire::actingAs($this->user)
        ->test(EventManager::class)
        ->assertSee('Test Event');
});

test('organizer can create an event', function () {
    Livewire::actingAs($this->user)
        ->test(EventManager::class)
        ->set('title', 'New Awesome Event')
        ->set('description', 'A really nice event')
        ->set('category', 'Music')
        ->set('start_date', now()->addDays(10)->format('Y-m-d'))
        ->set('time', '20:00')
        ->set('city', 'Antwerp')
        ->set('country', 'Belgium')
        ->set('capacity', 500)
        ->call('saveEvent');

    expect(Event::where('title', 'New Awesome Event')->exists())->toBeTrue();
});

test('organizer can edit an event', function () {
    $event = Event::create([
        'organizer_id' => $this->organizer->id,
        'title' => 'Old Title',
        'slug' => 'old-title',
        'category' => 'Music',
        'city' => 'Ghent',
        'country' => 'Belgium',
        'start_date' => now()->addDays(5),
        'time' => '20:00',
        'capacity' => 100,
        'status' => EventStatus::DRAFT,
    ]);

    Livewire::actingAs($this->user)
        ->test(EventManager::class)
        ->call('editEvent', $event->id)
        ->set('title', 'Updated Title')
        ->call('saveEvent');

    expect($event->refresh()->title)->toEqual('Updated Title');
});

test('organizer can soft delete and restore an event', function () {
    $event = Event::create([
        'organizer_id' => $this->organizer->id,
        'title' => 'To be deleted',
        'slug' => 'to-be-deleted',
        'category' => 'Comedy',
        'city' => 'Hasselt',
        'country' => 'Belgium',
        'start_date' => now()->addDays(5),
        'capacity' => 100,
        'status' => EventStatus::DRAFT,
    ]);

    $component = Livewire::actingAs($this->user)
        ->test(EventManager::class);

    $component->call('deleteEvent', $event->id);
    expect($event->refresh()->trashed())->toBeTrue();

    $component->call('restoreEvent', $event->id);
    expect($event->refresh()->trashed())->toBeFalse();
});

test('organizer can toggle publish status', function () {
    $event = Event::create([
        'organizer_id' => $this->organizer->id,
        'title' => 'Draft Event',
        'slug' => 'draft-event',
        'category' => 'Tech',
        'city' => 'Leuven',
        'country' => 'Belgium',
        'start_date' => now()->addDays(5),
        'capacity' => 100,
        'status' => EventStatus::DRAFT,
    ]);

    Livewire::actingAs($this->user)
        ->test(EventManager::class)
        ->call('togglePublish', $event->id);

    expect($event->refresh()->status)->toEqual(EventStatus::PUBLISHED);
});
