<?php

use App\Livewire\Organizer\PayoutManager;
use App\Models\Event;
use App\Models\Order;
use App\Models\Organizer;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

it('renders the organizer payout manager component', function () {
    $user = User::factory()->create(['role' => 'organizer']);
    Organizer::factory()->create(['user_id' => $user->id]);

    Livewire::actingAs($user)
        ->test(PayoutManager::class)
        ->assertStatus(200)
        ->assertSee('Payouts')
        ->assertSee('Available Balance');
});

it('can submit a payout request successfully', function () {
    $user = User::factory()->create(['role' => 'organizer']);
    $organizer = Organizer::factory()->create(['user_id' => $user->id]);
    $event = Event::factory()->create(['organizer_id' => $organizer->id]);

    // Give organizer some balance
    Order::factory()->create([
        'organizer_id' => $organizer->id,
        'event_id' => $event->id,
        'status' => 'paid',
        'total_amount' => 500.00,
    ]);

    Livewire::actingAs($user)
        ->test(PayoutManager::class)
        ->set('amount', 200.00)
        ->set('bank_details', 'NL91 INGB 0000 0000 00')
        ->call('submitRequest')
        ->assertHasNoErrors();

    $this->assertDatabaseHas('payout_requests', [
        'organizer_id' => $organizer->id,
        'amount' => 200.00,
        'status' => 'pending',
    ]);
});

it('prevents requesting more than available balance', function () {
    $user = User::factory()->create(['role' => 'organizer']);
    $organizer = Organizer::factory()->create(['user_id' => $user->id]);
    $event = Event::factory()->create(['organizer_id' => $organizer->id]);

    // Give organizer some balance
    Order::factory()->create([
        'organizer_id' => $organizer->id,
        'event_id' => $event->id,
        'status' => 'paid',
        'total_amount' => 100.00,
    ]);

    Livewire::actingAs($user)
        ->test(PayoutManager::class)
        ->set('amount', 150.00)
        ->set('bank_details', 'NL91 INGB 0000 0000 00')
        ->call('submitRequest')
        ->assertHasErrors(['amount']);

    $this->assertDatabaseMissing('payout_requests', [
        'organizer_id' => $organizer->id,
        'amount' => 150.00,
    ]);
});
