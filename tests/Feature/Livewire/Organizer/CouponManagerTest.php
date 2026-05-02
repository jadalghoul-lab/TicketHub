<?php

use App\Livewire\Organizer\CouponManager;
use App\Models\Coupon;
use App\Models\Organizer;
use App\Models\User;
use App\Enums\Role;
use Livewire\Livewire;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->user = User::factory()->create(['role' => Role::ORGANIZER]);
    $this->organizer = Organizer::factory()->create(['user_id' => $this->user->id]);
});

test('organizer can see their coupons', function () {
    $coupon = Coupon::create([
        'organizer_id' => $this->organizer->id,
        'code' => 'TEST10',
        'type' => 'percentage',
        'value' => 10,
    ]);

    Livewire::actingAs($this->user)
        ->test(CouponManager::class)
        ->assertSee('TEST10')
        ->assertSee('10.00%');
});

test('organizer can create a coupon', function () {
    Livewire::actingAs($this->user)
        ->test(CouponManager::class)
        ->set('code', 'SAVE50')
        ->set('type', 'percentage')
        ->set('value', 50)
        ->call('saveCoupon')
        ->assertHasNoErrors()
        ->assertSet('showModal', false);

    expect(Coupon::where('code', 'SAVE50')->exists())->toBeTrue();
});

test('it validates unique coupon code', function () {
    Coupon::create([
        'organizer_id' => $this->organizer->id,
        'code' => 'EXISTS',
        'type' => 'fixed',
        'value' => 5,
    ]);

    Livewire::actingAs($this->user)
        ->test(CouponManager::class)
        ->set('code', 'EXISTS')
        ->call('saveCoupon')
        ->assertHasErrors(['code' => 'unique']);
});

test('organizer can edit a coupon', function () {
    $coupon = Coupon::create([
        'organizer_id' => $this->organizer->id,
        'code' => 'OLD',
        'type' => 'fixed',
        'value' => 10,
    ]);

    Livewire::actingAs($this->user)
        ->test(CouponManager::class)
        ->call('editCoupon', $coupon->id)
        ->set('value', 15)
        ->call('saveCoupon')
        ->assertHasNoErrors();

    expect($coupon->refresh()->value)->toEqual(15);
});

test('organizer can delete a coupon', function () {
    $coupon = Coupon::create([
        'organizer_id' => $this->organizer->id,
        'code' => 'DELETE-ME',
        'type' => 'fixed',
        'value' => 10,
    ]);

    Livewire::actingAs($this->user)
        ->test(CouponManager::class)
        ->call('deleteCoupon', $coupon->id);

    expect(Coupon::where('id', $coupon->id)->exists())->toBeFalse();
});

test('organizer can toggle once per customer restriction', function () {
    Livewire::actingAs($this->user)
        ->test(CouponManager::class)
        ->set('code', 'ONCEONLY')
        ->set('type', 'percentage')
        ->set('value', 10)
        ->set('once_per_customer', true)
        ->call('saveCoupon')
        ->assertHasNoErrors();

    expect(Coupon::where('code', 'ONCEONLY')->first()->once_per_customer)->toBeTrue();
});
