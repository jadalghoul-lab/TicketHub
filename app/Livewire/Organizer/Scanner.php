<?php

namespace App\Livewire\Organizer;

use App\Models\Event;
use App\Models\Ticket;
use App\Services\TicketService;
use Livewire\Attributes\Layout;
use Livewire\Component;

class Scanner extends Component
{
    public Event $event;

    public string $manualCode = '';

    public ?array $scanResult = null;

    public ?Ticket $lastTicket = null;

    public int $totalTickets = 0;

    public int $checkedInCount = 0;

    public function mount(Event $event)
    {
        $this->event = $event;

        // Check if user is the organizer
        if (auth()->user()->organizer?->id !== $event->organizer_id) {
            abort(403);
        }

        $this->refreshStats();
    }

    public function refreshStats()
    {
        $this->totalTickets = Ticket::where('event_id', $this->event->id)->count();
        $this->checkedInCount = Ticket::where('event_id', $this->event->id)->where('status', 'used')->count();
    }

    public function scan(?string $code = null)
    {
        $code = $code ?: $this->manualCode;

        if (! $code) {
            return;
        }

        $ticketService = app(TicketService::class);
        $result = $ticketService->validateAndCheckIn(
            $code,
            $this->event->id,
            auth()->id()
        );

        $this->scanResult = $result;
        $this->lastTicket = $result['ticket'] ?? null;
        $this->manualCode = '';

        if ($result['success']) {
            $this->refreshStats();
            $this->dispatch('scan-success');
        } else {
            $this->dispatch('scan-error');
        }

        $this->dispatch('reset-result');
    }

    #[Layout('layouts.app')]
    public function render()
    {
        return view('livewire.organizer.scanner');
    }
}
