<?php

namespace App\Services;

use App\Models\Venue;
use App\Models\Zone;

class ZoneService
{
    /**
     * Create a new zone for a venue.
     */
    public function createZone(Venue $venue, array $data): Zone
    {
        $zone = new Zone($data);
        $zone->venue_id = $venue->id;
        $zone->save();

        return $zone;
    }

    /**
     * Update an existing zone.
     */
    public function updateZone(Zone $zone, array $data): Zone
    {
        $zone->update($data);

        return $zone;
    }
}
