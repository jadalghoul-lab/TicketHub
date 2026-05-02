<?php

namespace App\Livewire\Public;

use App\Models\Ticket;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class CartIcon extends Component
{
    public $ticketCount = 0;

    protected $listeners = ['orderPlaced' => 'updateCount'];

    public function mount()
    {
        $this->updateCount();
    }

    public function updateCount()
    {
        if (Auth::check()) {
            $this->ticketCount = Ticket::where('user_id', Auth::id())->count();
        } else {
            $this->ticketCount = 0;
        }
    }

    public function render()
    {
        return view('livewire.public.cart-icon');
    }
}
