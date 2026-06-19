<?php

use App\Models\Order;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('allows customer to download their ticket as PDF', function () {
    $user = User::factory()->create();

    // Create an order for the user
    $order = Order::factory()->create(['user_id' => $user->id]);

    // Create a ticket for that order
    $ticket = Ticket::factory()->create(['order_id' => $order->id]);

    $response = $this->actingAs($user)
        ->get(route('public.tickets.pdf', $ticket->id));

    $response->assertStatus(200);
    $response->assertHeader('Content-Type', 'application/pdf');
    $response->assertHeader('Content-Disposition', 'attachment; filename=ticket-'.$ticket->ticket_number.'.pdf');
});

it('prevents a customer from downloading someone elses ticket PDF', function () {
    $owner = User::factory()->create();
    $otherUser = User::factory()->create();

    $order = Order::factory()->create(['user_id' => $owner->id]);
    $ticket = Ticket::factory()->create(['order_id' => $order->id]);

    $response = $this->actingAs($otherUser)
        ->get(route('public.tickets.pdf', $ticket->id));

    $response->assertStatus(403);
});

it('requires authentication to download PDF', function () {
    $order = Order::factory()->create();
    $ticket = Ticket::factory()->create(['order_id' => $order->id]);

    $response = $this->get(route('public.tickets.pdf', $ticket->id));

    $response->assertRedirect('/login');
});
