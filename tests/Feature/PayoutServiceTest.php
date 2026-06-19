<?php

use App\Models\Event;
use App\Models\Order;
use App\Models\Organizer;
use App\Models\PayoutRequest;
use App\Services\PayoutService;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('calculates available balance correctly', function () {
    $organizer = Organizer::factory()->create();
    $event = Event::factory()->create(['organizer_id' => $organizer->id]);

    // Create some paid orders
    Order::factory()->create([
        'organizer_id' => $organizer->id,
        'event_id' => $event->id,
        'status' => 'paid',
        'total_amount' => 100.00,
    ]);

    Order::factory()->create([
        'organizer_id' => $organizer->id,
        'event_id' => $event->id,
        'status' => 'paid',
        'total_amount' => 50.00,
    ]);

    // Unpaid order should be ignored
    Order::factory()->create([
        'organizer_id' => $organizer->id,
        'event_id' => $event->id,
        'status' => 'pending',
        'total_amount' => 200.00,
    ]);

    $service = new PayoutService;
    $balance = $service->getAvailableBalance($organizer->id);

    expect($balance)->toBe(150.00);
});

it('deducts requested payouts from available balance', function () {
    $organizer = Organizer::factory()->create();
    $event = Event::factory()->create(['organizer_id' => $organizer->id]);

    Order::factory()->create([
        'organizer_id' => $organizer->id,
        'event_id' => $event->id,
        'status' => 'paid',
        'total_amount' => 200.00,
    ]);

    PayoutRequest::create([
        'organizer_id' => $organizer->id,
        'amount' => 50.00,
        'status' => 'pending',
        'bank_details' => 'BE1234',
    ]);

    $service = new PayoutService;
    $balance = $service->getAvailableBalance($organizer->id);

    // 200 - 50 = 150
    expect($balance)->toBe(150.00);
});

it('prevents requesting payout more than available balance', function () {
    $organizer = Organizer::factory()->create();
    $event = Event::factory()->create(['organizer_id' => $organizer->id]);

    Order::factory()->create([
        'organizer_id' => $organizer->id,
        'event_id' => $event->id,
        'status' => 'paid',
        'total_amount' => 100.00,
    ]);

    $service = new PayoutService;
    $result = $service->requestPayout($organizer, 150.00, 'BE1234');

    expect($result['success'])->toBeFalse()
        ->and($result['message'])->toContain('Insufficient funds');
});

it('creates payout request successfully', function () {
    $organizer = Organizer::factory()->create();
    $event = Event::factory()->create(['organizer_id' => $organizer->id]);

    Order::factory()->create([
        'organizer_id' => $organizer->id,
        'event_id' => $event->id,
        'status' => 'paid',
        'total_amount' => 100.00,
    ]);

    $service = new PayoutService;
    $result = $service->requestPayout($organizer, 50.00, 'BE1234');

    expect($result['success'])->toBeTrue()
        ->and($result['payout']->amount)->toEqual(50.00)
        ->and($result['payout']->status)->toBe('pending');

    $this->assertDatabaseHas('payout_requests', [
        'organizer_id' => $organizer->id,
        'amount' => 50.00,
        'status' => 'pending',
    ]);
});
