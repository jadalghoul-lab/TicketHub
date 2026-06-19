<?php

namespace Database\Factories;

use App\Models\Event;
use App\Models\Order;
use App\Models\Ticket;
use App\Models\TicketType;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class TicketFactory extends Factory
{
    protected $model = Ticket::class;

    public function definition(): array
    {
        return [
            'order_id' => Order::factory(),
            'event_id' => Event::factory(),
            'ticket_type_id' => TicketType::factory(),
            'user_id' => User::factory(),
            'uuid' => (string) Str::uuid(),
            'ticket_number' => 'TKT-'.strtoupper(Str::random(8)),
            'status' => 'valid',
            'scanned_at' => null,
            'scanned_by' => null,
        ];
    }
}
