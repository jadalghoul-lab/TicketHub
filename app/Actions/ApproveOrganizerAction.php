<?php

namespace App\Actions;

use App\Models\Organizer;

class ApproveOrganizerAction
{
    /**
     * Approve or reject an organizer.
     *
     * @param  string  $status  'active' or 'inactive'
     */
    public function execute(Organizer $organizer, string $status = 'active'): Organizer
    {
        if (! in_array($status, ['active', 'inactive'])) {
            throw new \InvalidArgumentException('Invalid status provided.');
        }

        $organizer->status = $status;
        $organizer->save();

        // Here we could trigger a notification or event
        // event(new OrganizerApproved($organizer));

        return $organizer;
    }
}
