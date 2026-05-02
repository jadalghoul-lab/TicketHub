<?php

namespace App\Services;

use App\Models\Venue;

class VenueService
{
    /**
     * Create a new venue.
     */
    public function createVenue(array $data): Venue
    {
        $venue = new Venue($data);
        $venue->save();

        return $venue;
    }

    /**
     * Update an existing venue.
     */
    public function updateVenue(Venue $venue, array $data): Venue
    {
        $venue->update($data);

        return $venue;
    }
}
