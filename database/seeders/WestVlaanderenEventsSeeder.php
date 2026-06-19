<?php

namespace Database\Seeders;

use App\Enums\EventStatus;
use App\Models\Event;
use App\Models\Organizer;
use App\Models\TicketType;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class WestVlaanderenEventsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get the first organizer to assign the events to
        $organizer = Organizer::first();

        if (! $organizer) {
            $this->command->error('No organizers found. Please run the TestDataSeeder first.');

            return;
        }

        $eventsData = [
            [
                'title' => 'Brugge Zomer Festival 2026',
                'category' => 'festival',
                'city' => 'Brugge',
                'description' => 'Experience the ultimate summer festival in the historic city of Bruges. Live music, food trucks, and unforgettable memories for everyone!',
                'start_date' => '2026-07-10',
                'end_date' => '2026-07-12',
                'time' => '2026-07-10 14:00:00',
                'image' => 'https://images.unsplash.com/photo-1514525253161-7a46d19cd819?q=80&w=1000&auto=format&fit=crop',
            ],
            [
                'title' => 'Oostende Beach Party',
                'category' => 'music',
                'city' => 'Oostende',
                'description' => 'Dance the night away at the biggest beach party in West Flanders. Featuring top international DJs and a spectacular light show by the sea.',
                'start_date' => '2026-08-05',
                'end_date' => '2026-08-06',
                'time' => '2026-08-05 18:00:00',
                'image' => 'https://images.unsplash.com/photo-1533174000276-2aaec198d085?q=80&w=1000&auto=format&fit=crop',
            ],
            [
                'title' => 'Kortrijk Tech & Innovation Meetup',
                'category' => 'workshop',
                'city' => 'Kortrijk',
                'description' => 'Join the largest gathering of tech enthusiasts and developers in West Flanders. Engage in hands-on workshops, networking, and explore the latest innovations.',
                'start_date' => '2026-09-15',
                'end_date' => '2026-09-15',
                'time' => '2026-09-15 09:00:00',
                'image' => 'https://images.unsplash.com/photo-1540575467063-178a50c2df87?q=80&w=1000&auto=format&fit=crop',
            ],
            [
                'title' => 'Ieper Food & Wine Tasting',
                'category' => 'festival',
                'city' => 'Ieper',
                'description' => 'Taste exquisite wines and culinary delights from local chefs in the beautiful city of Ypres. A perfect evening for food lovers.',
                'start_date' => '2026-07-28',
                'end_date' => '2026-07-28',
                'time' => '2026-07-28 17:00:00',
                'image' => 'https://images.unsplash.com/photo-1555939594-58d7cb561ad1?q=80&w=1000&auto=format&fit=crop',
            ],
        ];

        foreach ($eventsData as $data) {
            $event = Event::create([
                'organizer_id' => $organizer->id,
                'title' => $data['title'],
                'slug' => Str::slug($data['title'].'-'.rand(1000, 9999)),
                'category' => $data['category'],
                'city' => $data['city'],
                'country' => 'Belgium',
                'start_date' => $data['start_date'],
                'end_date' => $data['end_date'],
                'time' => $data['time'],
                'description' => $data['description'],
                'image' => $data['image'],
                'status' => EventStatus::PUBLISHED,
                'refund_deadline' => date('Y-m-d H:i:s', strtotime('-7 days', strtotime($data['start_date']))),
            ]);

            // Create Ticket Types for the event
            TicketType::create([
                'event_id' => $event->id,
                'name' => 'Standard Admission',
                'price' => rand(25, 45),
                'quantity' => rand(200, 500),
            ]);

            TicketType::create([
                'event_id' => $event->id,
                'name' => 'VIP Access',
                'price' => rand(80, 150),
                'quantity' => rand(20, 50),
            ]);

            $this->command->info("Created event: {$event->title}");
        }
    }
}
