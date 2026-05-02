<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\User;
use App\Models\Event;
use App\Models\Organizer;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class OrderFactory extends Factory
{
    protected $model = Order::class;

    public function definition(): array
    {
        return [
            'organizer_id' => Organizer::factory(),
            'event_id' => Event::factory(),
            'user_id' => User::factory(),
            'order_number' => 'ORD-' . strtoupper(Str::random(10)),
            'total_amount' => $this->faker->numberBetween(50, 200),
            'status' => 'paid',
            'payment_intent_id' => 'pi_' . Str::random(24),
        ];
    }
}
