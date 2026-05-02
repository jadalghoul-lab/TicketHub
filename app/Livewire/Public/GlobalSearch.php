<?php

namespace App\Livewire\Public;

use App\Models\Event;
use Livewire\Component;

class GlobalSearch extends Component
{
    public $search = '';
    public $city = '';
    public $results = [];
    public $showDropdown = false;

    public function updatedSearch()
    {
        $this->fetchResults();
    }

    public function updatedCity()
    {
        $this->fetchResults();
    }

    private function fetchResults()
    {
        if (strlen($this->search) >= 2 || strlen($this->city) >= 2) {
            $query = Event::published()
                ->withoutGlobalScopes()
                ->with(['venue', 'ticketTypes']);

            if ($this->search) {
                $query->where(function($q) {
                    $q->where('title', 'like', '%' . $this->search . '%')
                      ->orWhere('description', 'like', '%' . $this->search . '%')
                      ->orWhere('category', 'like', '%' . $this->search . '%');
                });
            }

            if ($this->city) {
                $query->where('city', 'like', '%' . $this->city . '%');
            }

            $this->results = $query->limit(5)->get();
            $this->showDropdown = true;
        } else {
            $this->results = [];
            $this->showDropdown = false;
        }
    }

    public function performSearch()
    {
        return redirect()->route('public.events.index', [
            'search' => $this->search,
            'city' => $this->city
        ]);
    }

    public function render()
    {
        return view('livewire.public.global-search');
    }
}
