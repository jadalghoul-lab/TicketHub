<?php

namespace App\Services;

use App\Models\Event;
use App\Models\TicketType;
use App\Models\Zone;

class TicketTypeService
{
    /**
     * Create a new ticket type for an event.
     */
    public function createTicketType(Event $event, array $data): TicketType
    {
        // If a zone is selected, ensure we don't exceed its capacity
        if (! empty($data['zone_id'])) {
            $zone = Zone::find($data['zone_id']);
            if ($zone && $data['quantity'] > $zone->capacity) {
                throw new \Exception('Ticket quantity exceeds zone capacity.');
            }
        }

        $ticketType = new TicketType($data);
        $ticketType->event_id = $event->id;
        $ticketType->save();

        return $ticketType;
    }

    /**
     * Update an existing ticket type.
     */
    public function updateTicketType(TicketType $ticketType, array $data): TicketType
    {
        $ticketType->update($data);

        return $ticketType;
    }

    /**
     * Check if a ticket type has enough available quantity.
     */
    public function hasAvailableStock(TicketType $ticketType, int $requestedQuantity): bool
    {
        // For phase 9, we assume basic quantity check. In the future, we might need to
        // calculate based on sold tickets or orders.
        return $ticketType->quantity >= $requestedQuantity;
    }
}
