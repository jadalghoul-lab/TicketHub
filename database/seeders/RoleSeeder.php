<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'role' => \App\Enums\Role::ADMIN,
        ]);

        \App\Models\User::factory()->create([
            'name' => 'Organizer User',
            'email' => 'organizer@example.com',
            'role' => \App\Enums\Role::ORGANIZER,
        ]);

        \App\Models\User::factory()->create([
            'name' => 'Customer User',
            'email' => 'customer@example.com',
            'role' => \App\Enums\Role::CUSTOMER,
        ]);
    }
}
