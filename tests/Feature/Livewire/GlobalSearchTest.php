<?php

use App\Livewire\Public\GlobalSearch;
use App\Models\Event;
use App\Enums\EventStatus;
use Livewire\Livewire;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('global search component renders', function () {
    Livewire::test(GlobalSearch::class)
        ->assertStatus(200);
});

test('it filters events by name', function () {
    $event1 = Event::factory()->create([
        'title' => 'Summer Concert',
        'status' => EventStatus::PUBLISHED
    ]);
    
    $event2 = Event::factory()->create([
        'title' => 'Winter Jazz',
        'status' => EventStatus::PUBLISHED
    ]);

    Livewire::test(GlobalSearch::class)
        ->set('search', 'Summer')
        ->assertSee($event1->title)
        ->assertDontSee($event2->title);
});

test('it filters events by city', function () {
    $event1 = Event::factory()->create([
        'city' => 'Amsterdam',
        'status' => EventStatus::PUBLISHED
    ]);
    
    $event2 = Event::factory()->create([
        'city' => 'Paris',
        'status' => EventStatus::PUBLISHED
    ]);

    Livewire::test(GlobalSearch::class)
        ->set('city', 'Amsterdam')
        ->assertSee($event1->title)
        ->assertDontSee($event2->title);
});

test('it does not show results with less than 2 characters', function () {
    Event::factory()->create([
        'title' => 'Concert',
        'status' => EventStatus::PUBLISHED
    ]);

    Livewire::test(GlobalSearch::class)
        ->set('search', 'C')
        ->assertSet('results', []);
});
