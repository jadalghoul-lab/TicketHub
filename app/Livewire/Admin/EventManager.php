<?php

namespace App\Livewire\Admin;

use App\Enums\EventStatus;
use App\Models\Event;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Encoders\WebpEncoder;
use Intervention\Image\Laravel\Facades\Image;
use Livewire\Component;
use Livewire\WithPagination;

class EventManager extends Component
{
    use \Livewire\WithFileUploads, WithPagination;

    public $search = '';

    public $statusFilter = 'all';

    public $showModal = false;

    public $isEditing = false;

    public $editingEventId;

    // Form fields
    public $title;

    public $category = 'Music';

    public $start_date;

    public $time;

    public $capacity;

    public $city;

    public $country;

    public $description;

    public $image;

    protected $rules = [
        'title' => 'required|min:5',
        'category' => 'required',
        'start_date' => 'required|date|after_or_equal:today',
        'capacity' => 'required|integer|min:1',
        'city' => 'required',
        'country' => 'required',
    ];

    public function editEvent($id)
    {
        $event = Event::withoutGlobalScopes()->findOrFail($id);
        $this->editingEventId = $event->id;
        $this->title = $event->title;
        $this->category = $event->category;
        $this->start_date = $event->start_date->format('Y-m-d');
        $this->time = $event->time ? $event->time->format('H:i') : null;
        $this->capacity = $event->capacity;
        $this->city = $event->city;
        $this->country = $event->country;
        $this->description = $event->description;

        $this->isEditing = true;
        $this->showModal = true;
    }

    public function saveEvent()
    {
        $this->validate();

        $event = Event::withoutGlobalScopes()->findOrFail($this->editingEventId);

        $data = [
            'title' => $this->title,
            'category' => $this->category,
            'start_date' => $this->start_date,
            'time' => $this->time,
            'capacity' => $this->capacity,
            'city' => $this->city,
            'country' => $this->country,
            'description' => $this->description,
        ];

        if ($this->image && ! is_string($this->image)) {
            $img = Image::read($this->image->getRealPath());
            $img->scaleDown(width: 1200);
            $encoded = $img->encode(new WebpEncoder(80));
            $filename = Str::random(40).'.webp';
            $path = 'events/'.$filename;
            Storage::disk('public')->put($path, (string) $encoded);
            $data['image'] = $path;
        }

        $event->update($data);

        $this->showModal = false;
        session()->flash('success', 'Event updated by Admin.');
    }

    public function deleteEvent($id)
    {
        $event = Event::withoutGlobalScopes()->findOrFail($id);
        $event->delete();
        session()->flash('success', 'Event deleted (soft deleted).');
    }

    public function restoreEvent($id)
    {
        $event = Event::withoutGlobalScopes()->onlyTrashed()->findOrFail($id);
        $event->restore();
        session()->flash('success', 'Event restored successfully.');
    }

    public function toggleBlock($id)
    {
        $event = Event::withoutGlobalScopes()->findOrFail($id);

        if ($event->status === EventStatus::BLOCKED) {
            $event->status = EventStatus::PUBLISHED;
            session()->flash('success', 'Event unblocked.');
        } else {
            $event->status = EventStatus::BLOCKED;
            session()->flash('success', 'Event blocked successfully.');
        }

        $event->save();
    }

    public function render()
    {
        $query = Event::withoutGlobalScopes()->with('organizer')->withTrashed();

        if ($this->search) {
            $query->where('title', 'like', '%'.$this->search.'%')
                ->orWhereHas('organizer', function ($q) {
                    $q->where('company_name', 'like', '%'.$this->search.'%');
                });
        }

        if ($this->statusFilter === 'published') {
            $query->where('status', EventStatus::PUBLISHED);
        } elseif ($this->statusFilter === 'blocked') {
            $query->where('status', EventStatus::BLOCKED);
        } elseif ($this->statusFilter === 'deleted') {
            $query->onlyTrashed();
        }

        return view('livewire.admin.event-manager', [
            'events' => $query->latest()->paginate(12),
        ])->layout('layouts.app');
    }
}
