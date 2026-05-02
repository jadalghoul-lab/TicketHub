<?php

namespace Database\Factories;

use App\Models\Venue;
use Illuminate\Database\Eloquent\Factories\Factory;

class VenueFactory extends Factory
{
    protected $model = Venue::class;

    public function definition(): array
    {
        return [
            'organizer_id' => \App\Models\Organizer::factory(),
            'name' => $this->faker->company() . ' Arena',
            'address' => $this->faker->address(),
            'city' => $this->faker->city(),
            'max_capacity' => $this->faker->numberBetween(100, 50000),
        ];
    }
}
