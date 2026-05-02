<?php

use App\Models\Coupon;
use App\Models\User;
use App\Models\Order;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('coupon identifies as expired', function () {
    $coupon = new Coupon([
        'expires_at' => now()->subDay(),
    ]);

    expect($coupon->isValid())->toBeFalse();
});

test('coupon identifies as maxed out', function () {
    $coupon = new Coupon([
        'max_usages' => 5,
        'usages_count' => 5,
    ]);

    expect($coupon->isValid())->toBeFalse();
});

test('coupon identifies as once per customer restricted', function () {
    $user = User::factory()->create();
    $coupon = Coupon::factory()->create([
        'once_per_customer' => true,
    ]);

    // Mock an order that used this coupon
    Order::factory()->create([
        'user_id' => $user->id,
        'coupon_id' => $coupon->id,
        'status' => 'paid',
    ]);

    expect($coupon->isValid($user))->toBeFalse();
});

test('coupon identifies as valid if everything is fine', function () {
    $coupon = new Coupon([
        'expires_at' => now()->addDay(),
        'max_usages' => 10,
        'usages_count' => 5,
    ]);

    expect($coupon->isValid())->toBeTrue();
});

test('coupon calculates percentage discount correctly', function () {
    $coupon = new Coupon([
        'type' => 'percentage',
        'value' => 20,
    ]);

    expect($coupon->calculateDiscount(100))->toEqual(20);
});

test('coupon calculates fixed discount correctly', function () {
    $coupon = new Coupon([
        'type' => 'fixed',
        'value' => 15,
    ]);

    expect($coupon->calculateDiscount(100))->toEqual(15);
});
