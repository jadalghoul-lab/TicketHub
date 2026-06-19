<?php

namespace App\Livewire\Organizer;

use App\Models\Event;
use Livewire\Component;

class TicketManager extends Component
{
    public Event $event;

    public $showModal = false;

    public $isEditing = false;

    public $ticketTypeId;

    // Form fields
    public $name;

    public $price;

    public $quantity;

    public $sales_start;

    public $sales_end;

    public $max_per_order = 5;

    public $description;

    public function mount(Event $event)
    {
        // Ensure the event belongs to the current organizer
        if ($event->organizer_id !== auth()->user()->organizer->id) {
            abort(403);
        }

        $this->event = $event;
    }

    protected function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:1',
            'sales_start' => 'nullable|date',
            'sales_end' => 'nullable|date|after_or_equal:sales_start',
            'max_per_order' => 'required|integer|min:1',
            'description' => 'nullable|string',
        ];
    }

    public function createTicket()
    {
        $this->resetValidation();
        $this->reset(['ticketTypeId', 'name', 'price', 'quantity', 'sales_start', 'sales_end', 'description']);
        $this->max_per_order = 5;
        $this->isEditing = false;
        $this->showModal = true;
    }

    public function editTicket($id)
    {
        $this->resetValidation();
        $ticket = $this->event->ticketTypes()->findOrFail($id);

        $this->ticketTypeId = $ticket->id;
        $this->name = $ticket->name;
        $this->price = $ticket->price;
        $this->quantity = $ticket->quantity;
        $this->sales_start = $ticket->sales_start ? $ticket->sales_start->format('Y-m-d\TH:i') : null;
        $this->sales_end = $ticket->sales_end ? $ticket->sales_end->format('Y-m-d\TH:i') : null;
        $this->max_per_order = $ticket->max_per_order;
        $this->description = $ticket->description;

        $this->isEditing = true;
        $this->showModal = true;
    }

    public function saveTicket()
    {
        $this->validate();

        $data = [
            'name' => $this->name,
            'price' => $this->price,
            'quantity' => $this->quantity,
            'sales_start' => $this->sales_start ?: null,
            'sales_end' => $this->sales_end ?: null,
            'max_per_order' => $this->max_per_order,
            'description' => $this->description,
        ];

        if ($this->isEditing) {
            $ticket = $this->event->ticketTypes()->findOrFail($this->ticketTypeId);
            $ticket->update($data);
            session()->flash('success', 'Ticket Type updated successfully.');
        } else {
            $this->event->ticketTypes()->create($data);
            session()->flash('success', 'Ticket Type created successfully.');
        }

        $this->showModal = false;
    }

    public function deleteTicket($id)
    {
        $ticket = $this->event->ticketTypes()->findOrFail($id);
        $ticket->delete();
        session()->flash('success', 'Ticket Type deleted.');
    }

    public function render()
    {
        return view('livewire.organizer.ticket-manager', [
            'ticketTypes' => $this->event->ticketTypes()->get(),
        ])->layout('layouts.app');
    }
}
