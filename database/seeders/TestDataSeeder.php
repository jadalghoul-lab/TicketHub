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

class TestDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Create a Test Customer
        $user = User::updateOrCreate(
            ['email' => 'customer@example.com'],
            [
                'name' => 'Jad Test Customer',
                'password' => Hash::make('password'),
                'role' => Role::CUSTOMER,
            ]
        );

        // 2. Create an Organizer
        $organizerUser = User::updateOrCreate(
            ['email' => 'organizer@example.com'],
            [
                'name' => 'Premium Events Ltd',
                'password' => Hash::make('password'),
                'role' => Role::ORGANIZER,
            ]
        );

        $organizer = Organizer::updateOrCreate(
            ['user_id' => $organizerUser->id],
            [
                'company_name' => 'Premium Events Ltd',
                'slug' => 'premium-events-ltd',
                'website' => 'https://premium-events.com',
                'contact_email' => 'info@premium-events.com',
            ]
        );

        // 3. Create Events
        $categories = ['music', 'festival', 'sports', 'workshop', 'theater'];
        $cities = ['Brussels', 'Antwerp', 'Ghent', 'Bruges', 'Liege', 'Paris', 'London'];
        $eventTitles = [
            'Neon Horizons World Tour', 'Tech Summit 2024', 'Championship Finals', 
            'Jazz Under The Stars', 'Summer Food Fest', 'Startup Pitch Night',
            'Modern Art Exhibition', 'Coding Workshop for Pros', 'Classical Night',
            'Winter Wonderland', 'Global Peace Conference', 'Rock The Bridge',
            'Theater Performance: Hamlet', 'Marathon 2024', 'E-Sports League',
            'Business Networking Hub', 'Digital Marketing Expo', 'Film Festival Opening',
            'Photography Masterclass', 'Gourmet Cooking Class'
        ];

        foreach ($eventTitles as $index => $title) {
            $category = $categories[$index % count($categories)];
            $city = $cities[$index % count($cities)];
            $startDate = now()->addDays(rand(-5, 40)); // Mix of past and future
            
            $event = Event::updateOrCreate(
                ['slug' => Str::slug($title)],
                [
                    'organizer_id' => $organizer->id,
                    'title' => $title,
                    'category' => $category,
                    'city' => $city,
                    'country' => 'Belgium',
                    'start_date' => $startDate,
                    'status' => rand(0, 10) > 2 ? EventStatus::PUBLISHED : EventStatus::DRAFT,
                    'description' => 'A professional and exciting event happening in ' . $city . '. Don\'t miss this opportunity to experience the best in ' . $category . '.',
                ]
            );

            // Create Ticket Types
            $prices = [0, 15, 25, 45, 99, 150];
            $ticketType = TicketType::updateOrCreate(
                ['event_id' => $event->id, 'name' => 'Standard Entry'],
                [
                    'price' => $prices[$index % count($prices)],
                    'quantity' => rand(0, 50), // Mix of available and sold out
                ]
            );

            // 4. Create an Order for the test user (only for the first 5 events)
            if ($index < 5) {
                $order = Order::create([
                    'organizer_id' => $organizer->id,
                    'event_id' => $event->id,
                    'user_id' => $user->id,
                    'order_number' => 'ORD-' . strtoupper(Str::random(8)),
                    'total_amount' => $ticketType->price,
                    'status' => 'paid',
                ]);

                // 5. Create a Ticket
                Ticket::create([
                    'order_id' => $order->id,
                    'event_id' => $event->id,
                    'ticket_type_id' => $ticketType->id,
                    'user_id' => $user->id,
                    'uuid' => (string) Str::uuid(),
                    'ticket_number' => 'TKT-' . strtoupper(Str::random(10)),
                    'status' => 'valid',
                ]);
            }
        }
    }
}
