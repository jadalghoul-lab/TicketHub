<?php

namespace App\Livewire\Public;

use App\Models\Event;
use Livewire\Component;
use Livewire\WithPagination;

class EventList extends Component
{
    use WithPagination;

    public $search = '';

    public $categories = [];

    public $dateRange = '';

    public $type = '';

    public $availableOnly = false;

    public $maxPrice = 500;

    public $city = '';

    public $sort = 'date';

    protected $queryString = [
        'search' => ['except' => ''],
        'categories' => ['except' => []],
        'dateRange' => ['except' => ''],
        'type' => ['except' => ''],
        'city' => ['except' => ''],
        'sort' => ['except' => 'date'],
    ];

    public function updated($propertyName)
    {
        if (in_array($propertyName, ['search', 'categories', 'dateRange', 'type', 'availableOnly', 'maxPrice', 'city', 'sort'])) {
            $this->resetPage();
        }
    }

    public function resetFilters()
    {
        $this->reset(['search', 'categories', 'dateRange', 'type', 'availableOnly', 'maxPrice', 'city', 'sort']);
        $this->resetPage();
    }

    public function render()
    {
        $query = Event::published()
            ->with(['venue', 'organizer', 'ticketTypes'])
            ->whereDate('start_date', '>=', now()->toDateString())
            ->withoutGlobalScopes();

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('title', 'like', '%'.$this->search.'%')
                    ->orWhere('description', 'like', '%'.$this->search.'%');
            });
        }

        if (! empty($this->categories)) {
            $query->whereIn('category', $this->categories);
        }

        if ($this->city) {
            $query->where('city', 'like', '%'.$this->city.'%');
        }

        if ($this->dateRange === 'today') {
            $query->whereDate('start_date', now()->toDateString());
        } elseif ($this->dateRange === 'weekend') {
            $query->whereBetween('start_date', [now()->startOfWeek()->addDays(5), now()->startOfWeek()->addDays(7)]);
        } elseif ($this->dateRange === '30days') {
            $query->where('start_date', '<=', now()->addDays(30))->where('start_date', '>=', now());
        }

        if ($this->type === 'free') {
            $query->whereHas('ticketTypes', fn ($q) => $q->where('price', 0));
        } elseif ($this->type === 'paid') {
            $query->whereHas('ticketTypes', fn ($q) => $q->where('price', '>', 0));
        }

        if ($this->maxPrice < 500) {
            $query->whereHas('ticketTypes', fn ($q) => $q->where('price', '<=', $this->maxPrice));
        }

        if ($this->availableOnly) {
            $query->whereHas('ticketTypes', fn ($q) => $q->where('quantity', '>', 0));
        }

        match ($this->sort) {
            'price_asc' => $query->join('ticket_types', 'events.id', '=', 'ticket_types.event_id')
                ->orderBy('ticket_types.price')->select('events.*'),
            'newest' => $query->orderByDesc('events.created_at'),
            default => $query->orderBy('start_date'),
        };

        return view('livewire.public.event-list', [
            'events' => $query->paginate(12),
        ]);
    }
}
