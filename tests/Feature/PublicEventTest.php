<?php

use App\Models\Event;
use App\Models\User;
use App\Enums\EventStatus;
use App\Enums\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('guest can view the events listing page', function () {
    $response = $this->get(route('public.events.index'));

    $response->assertStatus(200);
});

test('guest can view a published event', function () {
    $event = Event::factory()->create([
        'status' => EventStatus::PUBLISHED
    ]);

    $response = $this->get(route('public.events.show', $event->slug));

    $response->assertStatus(200)
             ->assertSee($event->title);
});

test('guest cannot view a draft event', function () {
    $event = Event::factory()->create([
        'status' => EventStatus::DRAFT
    ]);

    $response = $this->get(route('public.events.show', $event->slug));

    $response->assertStatus(404);
});

test('admin can view a draft event', function () {
    $admin = User::factory()->create(['role' => Role::ADMIN]);
    $event = Event::factory()->create([
        'status' => EventStatus::DRAFT
    ]);

    $response = $this->actingAs($admin)->get(route('public.events.show', $event->slug));

    $response->assertStatus(200)
             ->assertSee($event->title);
});

test('admin can view a trashed event', function () {
    $admin = User::factory()->create(['role' => Role::ADMIN]);
    $event = Event::factory()->create([
        'status' => EventStatus::PUBLISHED
    ]);
    $event->delete();

    $response = $this->actingAs($admin)->get(route('public.events.show', $event->slug));

    $response->assertStatus(200)
             ->assertSee($event->title);
});
