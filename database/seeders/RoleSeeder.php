<?php

namespace Database\Seeders;

use App\Enums\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'role' => Role::ADMIN,
        ]);

        User::factory()->create([
            'name' => 'Organizer User',
            'email' => 'organizer@example.com',
            'role' => Role::ORGANIZER,
        ]);

        User::factory()->create([
            'name' => 'Customer User',
            'email' => 'customer@example.com',
            'role' => Role::CUSTOMER,
        ]);
    }
}
