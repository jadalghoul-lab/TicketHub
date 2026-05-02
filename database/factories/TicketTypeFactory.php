<?php

namespace Database\Factories;

use App\Models\Event;
use App\Models\TicketType;
use Illuminate\Database\Eloquent\Factories\Factory;

class TicketTypeFactory extends Factory
{
    protected $model = TicketType::class;

    public function definition(): array
    {
        return [
            'event_id' => Event::factory(),
            'name' => 'General Admission',
            'price' => $this->faker->numberBetween(10, 100),
            'quantity' => $this->faker->numberBetween(50, 500),
        ];
    }
}
