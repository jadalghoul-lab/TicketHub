<?php

namespace App\Livewire\Public;

use App\Models\Order;
use App\Models\RefundRequest;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class RefundRequestForm extends Component
{
    public Order $order;

    public $reason = '';

    public $showModal = false;

    protected $rules = [
        'reason' => 'required|min:10',
    ];

    public function mount(Order $order)
    {
        $this->order = $order;
    }

    public function submit()
    {
        $this->validate();

        // Check if already requested
        if (RefundRequest::where('order_id', $this->order->id)->exists()) {
            session()->flash('error', 'A refund request already exists for this order.');
            $this->showModal = false;

            return;
        }

        // Check conditions (before event start and refund deadline)
        if ($this->order->event->start_date->isPast()) {
            session()->flash('error', 'Refunds are not allowed after the event has started.');
            $this->showModal = false;

            return;
        }

        if ($this->order->event->refund_deadline && $this->order->event->refund_deadline->isPast()) {
            session()->flash('error', 'The refund deadline for this event has passed.');
            $this->showModal = false;

            return;
        }

        RefundRequest::create([
            'order_id' => $this->order->id,
            'user_id' => Auth::id(),
            'reason' => $this->reason,
            'status' => 'pending',
        ]);

        session()->flash('success', 'Your refund request has been submitted and is pending approval.');
        $this->showModal = false;
        $this->reason = '';
    }

    public function render()
    {
        return view('livewire.public.refund-request-form');
    }
}
