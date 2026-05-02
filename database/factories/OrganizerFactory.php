<?php

namespace Database\Factories;

use App\Models\Organizer;
use App\Models\User;
use App\Enums\Role;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class OrganizerFactory extends Factory
{
    protected $model = Organizer::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory()->create(['role' => Role::ORGANIZER])->id,
            'company_name' => $this->faker->company(),
            'slug' => fn (array $attributes) => Str::slug($attributes['company_name']),
            'contact_email' => $this->faker->companyEmail(),
            'status' => 'active',
        ];
    }
}
