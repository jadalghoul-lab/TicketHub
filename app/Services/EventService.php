<?php

namespace App\Services;

use App\Models\Event;
use Illuminate\Support\Str;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use App\Enums\EventStatus;

class EventService
{
    /**
     * Create a new event.
     */
    public function createEvent(array $data, ?UploadedFile $image = null): Event
    {
        $event = new Event($data);
        $event->slug = Str::slug($data['title'] . '-' . uniqid());
        
        if ($image) {
            $event->image = $image->store('event_images', 'public');
        }

        $event->save();

        return $event;
    }

    /**
     * Update an existing event.
     */
    public function updateEvent(Event $event, array $data, ?UploadedFile $image = null): Event
    {
        $event->fill($data);

        // Update slug if title changed
        if (isset($data['title']) && $event->isDirty('title')) {
            $event->slug = Str::slug($data['title'] . '-' . uniqid());
        }

        if ($image) {
            if ($event->image && Storage::disk('public')->exists($event->image)) {
                Storage::disk('public')->delete($event->image);
            }
            $event->image = $image->store('event_images', 'public');
        }

        $event->save();

        return $event;
    }

    /**
     * Change the publish status of an event.
     */
    public function changeStatus(Event $event, EventStatus $status): bool
    {
        $event->status = $status;
        return $event->save();
    }

    /**
     * Duplicate an existing event.
     */
    public function duplicateEvent(Event $originalEvent): Event
    {
        $newEvent = $originalEvent->replicate();
        
        $newEvent->title = $originalEvent->title . ' (Copy)';
        $newEvent->slug = Str::slug($newEvent->title . '-' . uniqid());
        $newEvent->status = EventStatus::DRAFT;
        
        // Let's copy the image file if it exists, to prevent shared file deletion issues
        if ($originalEvent->image && Storage::disk('public')->exists($originalEvent->image)) {
            $extension = pathinfo($originalEvent->image, PATHINFO_EXTENSION);
            $newImagePath = 'event_images/' . Str::uuid() . '.' . $extension;
            Storage::disk('public')->copy($originalEvent->image, $newImagePath);
            $newEvent->image = $newImagePath;
        }

        $newEvent->save();

        return $newEvent;
    }
}
