<?php

namespace App\Livewire\Organizer;

use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use App\Models\Event;
use Illuminate\Support\Str;

class EventManager extends Component
{
    use WithFileUploads, WithPagination;

    public $showModal = false;
    public $isEditing = false;
    
    // Form fields
    public $eventId;
    public $title;
    public $description;
    public $category = 'Music';
    public $start_date;
    public $time;
    public $city;
    public $country;
    public $capacity;
    public $image;

    // Filters
    public $statusFilter = 'active';

    protected function rules()
    {
        return [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category' => 'required|string',
            'start_date' => 'required|date',
            'time' => 'required',
            'city' => 'required|string',
            'country' => 'required|string',
            'capacity' => 'required|integer|min:1',
            'image' => 'nullable|image|max:10240', // Increased to 10MB to allow auto-resizing of large images
        ];
    }

    public function createEvent()
    {
        $this->resetValidation();
        $this->reset(['eventId', 'title', 'description', 'category', 'start_date', 'time', 'city', 'country', 'capacity', 'image']);
        $this->isEditing = false;
        $this->showModal = true;
    }

    public function editEvent($id)
    {
        $this->resetValidation();
        
        $event = Event::withTrashed()->where('organizer_id', auth()->user()->organizer->id)->findOrFail($id);
        $this->eventId = $event->id;
        $this->title = $event->title;
        $this->description = $event->description;
        $this->category = $event->category;
        $this->start_date = $event->start_date->format('Y-m-d');
        $this->time = $event->time ? $event->time->format('H:i') : null;
        $this->city = $event->city;
        $this->country = $event->country;
        $this->capacity = $event->capacity;
        
        $this->isEditing = true;
        $this->showModal = true;
    }

    public function saveEvent()
    {
        $this->validate();

        $organizer = auth()->user()->organizer;

        $data = [
            'organizer_id' => $organizer->id,
            'title' => $this->title,
            'slug' => Str::slug($this->title) . '-' . rand(100, 999),
            'description' => $this->description,
            'category' => $this->category,
            'start_date' => $this->start_date,
            'time' => $this->time,
            'city' => $this->city,
            'country' => $this->country,
            'capacity' => $this->capacity,
            'status' => 'draft',
        ];

        if ($this->image) {
            $manager = \Intervention\Image\ImageManager::usingDriver(\Intervention\Image\Drivers\Gd\Driver::class);
            $img = $manager->decodePath($this->image->getRealPath());
            
            // Resize image if it's wider than 1200px, keeping aspect ratio
            $img->scaleDown(width: 1200);
            
            // Convert to highly optimized WebP format
            $encoded = $img->encode(new \Intervention\Image\Encoders\WebpEncoder(80));
            
            $filename = Str::random(40) . '.webp';
            $path = 'events/' . $filename;
            
            \Illuminate\Support\Facades\Storage::disk('public')->put($path, (string) $encoded);
            
            $data['image'] = $path;
        }

        if ($this->isEditing) {
            $event = Event::withTrashed()->where('organizer_id', $organizer->id)->findOrFail($this->eventId);
            unset($data['slug']); // Keep original slug
            if (!$this->image) {
                unset($data['image']);
            }
            $event->update($data);
            session()->flash('success', 'Event updated successfully.');
        } else {
            Event::create($data);
            session()->flash('success', 'Event created successfully.');
        }

        $this->showModal = false;
    }

    public function deleteEvent($id)
    {
        $event = Event::where('organizer_id', auth()->user()->organizer->id)->findOrFail($id);
        $event->delete();
        session()->flash('success', 'Event deleted (soft deleted).');
    }

    public function restoreEvent($id)
    {
        $event = Event::onlyTrashed()->where('organizer_id', auth()->user()->organizer->id)->findOrFail($id);
        $event->restore();
        session()->flash('success', 'Event restored successfully.');
    }

    public function togglePublish($id)
    {
        $event = Event::where('organizer_id', auth()->user()->organizer->id)->findOrFail($id);
        $event->status = $event->status === \App\Enums\EventStatus::PUBLISHED 
            ? \App\Enums\EventStatus::DRAFT 
            : \App\Enums\EventStatus::PUBLISHED;
        $event->save();
        session()->flash('success', 'Event status updated.');
    }

    public function render()
    {
        $query = Event::withTrashed()->where('organizer_id', auth()->user()->organizer->id);

        if ($this->statusFilter === 'active') {
            $query->whereNull('deleted_at');
        } elseif ($this->statusFilter === 'deleted') {
            $query->onlyTrashed();
        }

        $events = $query->orderBy('created_at', 'desc')->paginate(10);

        return view('livewire.organizer.event-manager', [
            'events' => $events
        ])->layout('layouts.app');
    }
}
