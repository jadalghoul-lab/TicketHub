<?php

namespace App\Policies;

use App\Models\TicketType;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class TicketTypePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true; // Usually public can see ticket types for an event
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, TicketType $ticketType): bool
    {
        return true;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->isOrganizer() && $user->organizer !== null;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, TicketType $ticketType): bool
    {
        if ($user->isAdmin()) return true;

        return $user->isOrganizer() && $user->organizer && $user->organizer->id === $ticketType->event->organizer_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, TicketType $ticketType): bool
    {
        if ($user->isAdmin()) return true;

        return $user->isOrganizer() && $user->organizer && $user->organizer->id === $ticketType->event->organizer_id;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, TicketType $ticketType): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, TicketType $ticketType): bool
    {
        return $user->isAdmin();
    }
}
