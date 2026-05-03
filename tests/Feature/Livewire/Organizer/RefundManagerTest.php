<?php

use App\Livewire\Organizer\RefundManager;
use App\Models\Event;
use App\Models\Organizer;
use App\Models\Order;
use App\Models\RefundRequest;
use App\Models\Ticket;
use App\Models\User;
use App\Enums\Role;
use App\Services\StripeService;
use Livewire\Livewire;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->user = User::factory()->create(['role' => Role::ORGANIZER]);
    $this->organizer = Organizer::factory()->create(['user_id' => $this->user->id]);
    $this->event = Event::factory()->create(['organizer_id' => $this->organizer->id]);
    
    $this->customer = User::factory()->create();
    $this->order = Order::factory()->create([
        'organizer_id' => $this->organizer->id,
        'event_id' => $this->event->id,
        'user_id' => $this->customer->id,
        'payment_intent_id' => 'pi_test_123',
        'total_amount' => 50.00,
        'status' => 'paid'
    ]);
    
    $this->ticket = Ticket::factory()->create([
        'order_id' => $this->order->id,
        'event_id' => $this->event->id,
        'user_id' => $this->customer->id,
        'status' => 'valid'
    ]);
    
    $this->refundRequest = RefundRequest::create([
        'order_id' => $this->order->id,
        'user_id' => $this->customer->id,
        'reason' => 'Change of plans',
        'status' => 'pending'
    ]);
});

test('organizer can approve refund request which triggers stripe', function () {
    // Mock Stripe Service
    $stripeMock = Mockery::mock(StripeService::class);
    $stripeMock->shouldReceive('refund')
        ->once()
        ->andReturn(['success' => true]);
        
    app()->instance(StripeService::class, $stripeMock);

    $component = Livewire::actingAs($this->user)
        ->test(RefundManager::class);
        
    $component->call('approve', $this->refundRequest->id)
        ->assertHasNoErrors()
        ->assertStatus(200);

    expect($this->refundRequest->refresh()->status)->toEqual('approved');
});

test('organizer can reject refund request', function () {
    $component = Livewire::actingAs($this->user)
        ->test(RefundManager::class);
        
    $component->call('reject', $this->refundRequest->id)
        ->assertHasNoErrors()
        ->assertStatus(200);

    expect($this->refundRequest->refresh()->status)->toEqual('rejected');
});

test('approve fails if stripe refund fails', function () {
    // Mock Stripe Service failure
    $stripeMock = Mockery::mock(StripeService::class);
    $stripeMock->shouldReceive('refund')
        ->once()
        ->andReturn(['success' => false, 'message' => 'Insufficient funds']);
        
    app()->instance(StripeService::class, $stripeMock);

    $component = Livewire::actingAs($this->user)
        ->test(RefundManager::class);
        
    $component->call('approve', $this->refundRequest->id);

    expect($this->refundRequest->refresh()->status)->toEqual('pending');
});
