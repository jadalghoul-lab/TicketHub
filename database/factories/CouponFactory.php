<?php

namespace Database\Factories;

use App\Models\Coupon;
use App\Models\Organizer;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class CouponFactory extends Factory
{
    protected $model = Coupon::class;

    public function definition(): array
    {
        return [
            'organizer_id' => Organizer::factory(),
            'code' => strtoupper(Str::random(8)),
            'type' => $this->faker->randomElement(['percentage', 'fixed']),
            'value' => $this->faker->numberBetween(5, 50),
            'expires_at' => now()->addMonth(),
            'max_usages' => 100,
            'usages_count' => 0,
            'once_per_customer' => false,
        ];
    }
}
