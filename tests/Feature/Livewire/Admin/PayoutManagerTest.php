<?php

use App\Models\User;
use App\Models\Organizer;
use App\Models\PayoutRequest;
use App\Livewire\Admin\PayoutManager;
use Livewire\Livewire;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('renders the admin payout manager component', function () {
    $admin = User::factory()->create(['role' => 'admin']);

    Livewire::actingAs($admin)
        ->test(PayoutManager::class)
        ->assertStatus(200)
        ->assertSee('Payout Requests Management');
});

it('can update payout request status', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $organizer = Organizer::factory()->create();
    
    $payout = PayoutRequest::create([
        'organizer_id' => $organizer->id,
        'amount' => 100.00,
        'status' => 'pending',
        'bank_details' => 'BE1234'
    ]);

    Livewire::actingAs($admin)
        ->test(PayoutManager::class)
        ->call('updateStatus', $payout->id, 'approved');

    $this->assertDatabaseHas('payout_requests', [
        'id' => $payout->id,
        'status' => 'approved'
    ]);
});

it('filters payouts by status', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $organizer = Organizer::factory()->create();
    
    $pending = PayoutRequest::create([
        'organizer_id' => $organizer->id,
        'amount' => 100.00,
        'status' => 'pending',
        'bank_details' => 'BE1234'
    ]);

    $paid = PayoutRequest::create([
        'organizer_id' => $organizer->id,
        'amount' => 50.00,
        'status' => 'paid',
        'bank_details' => 'BE1234'
    ]);

    Livewire::actingAs($admin)
        ->test(PayoutManager::class)
        ->set('statusFilter', 'paid')
        ->assertSee('50.00')
        ->assertDontSee('100.00'); // Assuming it doesn't render the pending one
});
