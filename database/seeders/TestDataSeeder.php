<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Organizer;
use App\Models\Event;
use App\Models\TicketType;
use App\Models\Order;
use App\Models\Ticket;
use App\Enums\Role;
use App\Enums\EventStatus;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class TestDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Disable foreign key checks for clean seed
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Ticket::truncate();
        Order::truncate();
        TicketType::truncate();
        Event::truncate();
        Organizer::truncate();
        User::whereIn('role', [Role::ADMIN, Role::ORGANIZER, Role::CUSTOMER])->delete();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // 1. Create 1 Admin
        User::create([
            'name' => 'System Admin',
            'email' => 'admin@tickethub.com',
            'password' => Hash::make('password'),
            'role' => Role::ADMIN,
        ]);

        // 2. Create 3 Organizers
        $organizers = [];
        $orgData = [
            ['name' => 'Global Sounds Events', 'company' => 'Global Sounds Ltd'],
            ['name' => 'Tech Connect Expo', 'company' => 'Tech Connect Inc'],
            ['name' => 'Sports Arena Group', 'company' => 'Arena Group SA'],
        ];

        foreach ($orgData as $data) {
            $user = User::create([
                'name' => $data['name'],
                'email' => Str::slug($data['name']) . '@example.com',
                'password' => Hash::make('password'),
                'role' => Role::ORGANIZER,
            ]);

            $organizers[] = Organizer::create([
                'user_id' => $user->id,
                'company_name' => $data['company'],
                'slug' => Str::slug($data['company']),
                'contact_email' => $user->email,
            ]);
        }

        // 3. Create 20 Events
        $eventTitles = [
            'Neon Horizons Tour', 'Cyber Security Summit', 'World Cup Qualifiers', 
            'Jazz in the Park', 'Food & Wine Festival', 'AI Innovation Night',
            'Abstract Art Gallery', 'Node.js Deep Dive', 'Opera Gala Night',
            'Alpine Ski Cup', 'Blockchain Expo', 'Retro Rock Revival',
            'Shakespeare: Macbeth', 'City Marathon 2024', 'Dota 2 Masters',
            'E-commerce Summit', 'Cloud Native Day', 'Sundance Film Opening',
            'Street Photo Workshop', 'Master Chef Class'
        ];

        $categories = ['music', 'festival', 'sports', 'workshop', 'theater'];
        $cities = ['Brussels', 'Antwerp', 'Ghent', 'Paris', 'London', 'Berlin'];
        
        $createdEvents = [];
        foreach ($eventTitles as $index => $title) {
            $org = $organizers[$index % count($organizers)];
            $event = Event::create([
                'organizer_id' => $org->id,
                'title' => $title,
                'slug' => Str::slug($title . '-' . rand(100, 999)),
                'category' => $categories[$index % count($categories)],
                'city' => $cities[$index % count($cities)],
                'country' => 'Belgium',
                'start_date' => now()->addDays(rand(-10, 60)),
                'status' => EventStatus::PUBLISHED,
                'description' => 'Experience the best of ' . $title . ' in ' . $cities[$index % count($cities)] . '.',
            ]);

            // Create 2 ticket types for each event
            TicketType::create([
                'event_id' => $event->id,
                'name' => 'Standard',
                'price' => rand(20, 50),
                'quantity' => 100,
            ]);

            TicketType::create([
                'event_id' => $event->id,
                'name' => 'VIP',
                'price' => rand(80, 200),
                'quantity' => 30,
            ]);

            $createdEvents[] = $event;
        }

        // 4. Create Customers
        $customers = [];
        for ($i = 0; $i < 20; $i++) {
            $customers[] = User::create([
                'name' => 'Customer ' . ($i + 1),
                'email' => 'customer' . ($i + 1) . '@example.com',
                'password' => Hash::make('password'),
                'role' => Role::CUSTOMER,
            ]);
        }

        // 5. Create 100 Orders and 300 Tickets
        for ($i = 0; $i < 100; $i++) {
            $customer = $customers[array_rand($customers)];
            $event = $createdEvents[array_rand($createdEvents)];
            $ticketType = $event->ticketTypes()->inRandomOrder()->first();
            $quantity = 3; // Exactly 3 tickets per order to reach 300 total

            $order = Order::create([
                'organizer_id' => $event->organizer_id,
                'event_id' => $event->id,
                'user_id' => $customer->id,
                'order_number' => 'ORD-' . strtoupper(Str::random(10)),
                'total_amount' => $ticketType->price * $quantity,
                'status' => 'paid',
            ]);

            for ($j = 0; $j < $quantity; $j++) {
                $isScanned = rand(1, 100) <= 30; // 30% chance to be scanned
                
                Ticket::create([
                    'order_id' => $order->id,
                    'event_id' => $event->id,
                    'ticket_type_id' => $ticketType->id,
                    'user_id' => $customer->id,
                    'uuid' => (string) Str::uuid(),
                    'ticket_number' => 'TKT-' . strtoupper(Str::random(12)),
                    'status' => $isScanned ? 'used' : 'valid',
                    'scanned_at' => $isScanned ? now()->subMinutes(rand(1, 120)) : null,
                    'scanned_by' => $isScanned ? $event->organizer->user_id : null,
                ]);
            }
        }
    }
}
