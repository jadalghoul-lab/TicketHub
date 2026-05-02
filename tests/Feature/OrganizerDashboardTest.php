<?php

use App\Models\User;
use App\Models\Organizer;
use App\Models\Event;
use App\Models\Order;
use App\Models\Ticket;
use App\Models\TicketType;
use App\Enums\EventStatus;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;

uses(RefreshDatabase::class);

test('guests cannot access organizer dashboard', function () {
    $this->get(route('organizer.dashboard'))->assertRedirect(route('login'));
});

test('customers cannot access organizer dashboard', function () {
    $customer = User::factory()->create(['role' => 'customer']);
    $this->actingAs($customer)->get(route('organizer.dashboard'))->assertForbidden();
});

test('organizers can access their dashboard', function () {
    $user = User::factory()->create(['role' => 'organizer']);
    $organizer = Organizer::create([
        'user_id' => $user->id,
        'company_name' => 'Test Company',
        'slug' => 'test-company',
        'stripe_account_id' => 'acct_123',
    ]);

    $this->actingAs($user)->get(route('organizer.dashboard'))->assertOk();
});

test('organizer dashboard displays correct statistics and data', function () {
    $user = User::factory()->create(['role' => 'organizer']);
    $organizer = Organizer::create([
        'user_id' => $user->id,
        'company_name' => 'Test Company',
        'slug' => 'test-company',
        'stripe_account_id' => 'acct_123',
    ]);

    $event = Event::create([
        'organizer_id' => $organizer->id,
        'title' => 'Test Event',
        'slug' => 'test-event',
        'description' => 'Test',
        'category' => 'Music',
        'city' => 'Berlin',
        'country' => 'Germany',
        'capacity' => 100,
        'status' => EventStatus::PUBLISHED,
        'start_date' => now()->addDays(5),
    ]);

    $ticketType = TicketType::create([
        'event_id' => $event->id,
        'name' => 'General',
        'price' => 50,
        'quantity' => 100,
    ]);

    $customer = User::factory()->create();
    
    // Create an order
    $order = Order::create([
        'user_id' => $customer->id,
        'event_id' => $event->id,
        'organizer_id' => $organizer->id,
        'order_number' => 'ORD-123',
        'status' => 'paid',
        'total_amount' => 100,
        'subtotal' => 100,
        'fees' => 0,
    ]);

    // Create 2 valid tickets
    for ($i=0; $i<2; $i++) {
        Ticket::create([
            'order_id' => $order->id,
            'event_id' => $event->id,
            'ticket_type_id' => $ticketType->id,
            'user_id' => $customer->id,
            'ticket_number' => 'TKT-' . $i,
            'uuid' => Str::uuid(),
            'status' => 'valid',
        ]);
    }

    // Create 1 used ticket for attendance rate
    Ticket::create([
        'order_id' => $order->id,
        'event_id' => $event->id,
        'ticket_type_id' => $ticketType->id,
        'user_id' => $customer->id,
        'ticket_number' => 'TKT-999',
        'uuid' => Str::uuid(),
        'status' => 'used',
    ]);

    $response = $this->actingAs($user)->get(route('organizer.dashboard', ['period' => 'month']));

    $response->assertOk()
        ->assertViewIs('organizer.dashboard')
        ->assertViewHasAll([
            'totalRevenue',
            'totalTickets',
            'attendanceRate',
            'events',
            'topEvents',
            'recentOrders',
            'upcomingEvents',
            'period'
        ]);

    $this->assertEquals(100, $response->viewData('totalRevenue'));
    $this->assertEquals(3, $response->viewData('totalTickets'));
    // Attendance rate = (1 used / 3 total) * 100 = 33.33...
    $this->assertEquals(33, $response->viewData('attendanceRate'));
    
    $this->assertCount(1, $response->viewData('events'));
    $this->assertCount(1, $response->viewData('topEvents'));
    $this->assertCount(1, $response->viewData('recentOrders'));
    $this->assertCount(1, $response->viewData('upcomingEvents'));
});
