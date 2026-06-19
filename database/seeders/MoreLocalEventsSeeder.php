<?php

namespace Database\Seeders;

use App\Enums\EventStatus;
use App\Models\Event;
use App\Models\Organizer;
use App\Models\TicketType;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class MoreLocalEventsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $organizer = Organizer::first();

        if (! $organizer) {
            $this->command->error('No organizers found. Please run the TestDataSeeder first.');

            return;
        }

        // We will download 5 high-quality images from Unsplash and save them LOCALLY in the storage folder
        // Then we will cycle these images across the 20 events to avoid downloading 20 times (which takes long).
        $imageUrls = [
            'https://images.unsplash.com/photo-1540039155732-684736dd6d46?q=80&w=1000&auto=format&fit=crop',
            'https://images.unsplash.com/photo-1516450360452-9312f5e86fc7?q=80&w=1000&auto=format&fit=crop',
            'https://images.unsplash.com/photo-1459749411175-04bf5292ceea?q=80&w=1000&auto=format&fit=crop',
            'https://images.unsplash.com/photo-1501281668745-f7f57925c3b4?q=80&w=1000&auto=format&fit=crop',
            'https://images.unsplash.com/photo-1506157786151-b8491531f063?q=80&w=1000&auto=format&fit=crop',
        ];

        $localImagePaths = [];

        $this->command->info('Downloading images and saving them locally in storage/app/public/events/ ...');
        foreach ($imageUrls as $index => $url) {
            $contents = @file_get_contents($url);
            if ($contents) {
                $filename = 'events/seeded-event-'.$index.'-'.Str::random(5).'.jpg';
                // Put file to the local 'public' disk
                Storage::disk('public')->put($filename, $contents);
                $localImagePaths[] = $filename;
                $this->command->info("Saved local image: {$filename}");
            } else {
                $this->command->warn("Could not download image index {$index}");
            }
        }

        if (empty($localImagePaths)) {
            $this->command->error('Failed to download any images locally.');

            return;
        }

        $eventTitles = [
            'Brussels Summer Fiesta', 'Antwerp Diamond Expo', 'Ghent Light Festival',
            'Leuven Tech Weekend', 'Hasselt Fashion Week', 'Mechelen Food Fest',
            'Namur Riverside Concert', 'Liege Night Run', 'Mons Cultural Fair',
            'Charleroi Indie Music', 'Ostend Jazz on the Beach', 'Bruges Open Air Cinema',
            'Ypres History Talk', 'Kortrijk Design Week', 'Roeselare Cycling Gala',
            'Aalst Carnival Pre-Party', 'Sint-Niklaas Balloon Fest', 'Tournai Art Exhibition',
            'Genk Urban Sports Event', 'Turnhout Comic Con',
        ];

        $categories = ['music', 'festival', 'sports', 'workshop', 'theater'];

        $this->command->info('Creating 20 events with dates after June 26, 2026...');

        foreach ($eventTitles as $i => $title) {
            // Current date is June 14, 2026.
            // Adding rand(15, 120) days guarantees the date is after June 29, 2026.
            $startDate = now()->addDays(rand(15, 120));

            // Pick one of the locally saved images
            $localImagePath = $localImagePaths[$i % count($localImagePaths)];

            $event = Event::create([
                'organizer_id' => $organizer->id,
                'title' => $title,
                'slug' => Str::slug($title.'-'.rand(1000, 9999)),
                'category' => $categories[$i % count($categories)],
                'city' => explode(' ', $title)[0], // Extract first word as city
                'country' => 'Belgium',
                'start_date' => $startDate->format('Y-m-d'),
                'end_date' => $startDate->copy()->addDays(rand(0, 2))->format('Y-m-d'),
                'time' => $startDate->format('Y-m-d 18:00:00'),
                'description' => 'Experience an unforgettable event! The '.$title.' will bring people together for a spectacular time. Make sure you get your tickets before they run out.',
                'image' => $localImagePath, // Save the LOCAL path in the database
                'status' => EventStatus::PUBLISHED,
                'refund_deadline' => $startDate->copy()->subDays(7)->format('Y-m-d H:i:s'),
            ]);

            TicketType::create([
                'event_id' => $event->id,
                'name' => 'Standard Access',
                'price' => rand(15, 45),
                'quantity' => rand(100, 500),
            ]);

            TicketType::create([
                'event_id' => $event->id,
                'name' => 'VIP Backstage',
                'price' => rand(80, 150),
                'quantity' => rand(20, 50),
            ]);

            $this->command->info("Created event: {$title} (Date: {$startDate->format('Y-m-d')})");
        }

        $this->command->info('All 20 events created successfully and images stored locally!');
    }
}
