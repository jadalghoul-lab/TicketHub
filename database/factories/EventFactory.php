<?php

namespace Database\Factories;

use App\Models\Event;
use App\Models\Organizer;
use App\Models\Venue;
use App\Enums\EventStatus;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class EventFactory extends Factory
{
    protected $model = Event::class;

    public function definition(): array
    {
        $title = $this->faker->sentence(3);
        return [
            'organizer_id' => Organizer::factory(),
            'venue_id' => Venue::factory(),
            'title' => $title,
            'slug' => Str::slug($title) . '-' . $this->faker->numberBetween(100, 999),
            'description' => $this->faker->paragraph(),
            'category' => $this->faker->randomElement(['music', 'sports', 'theater', 'festival', 'other']),
            'start_date' => now()->addDays($this->faker->numberBetween(1, 60)),
            'time' => '20:00:00',
            'city' => $this->faker->city(),
            'status' => EventStatus::PUBLISHED,
        ];
    }
}
