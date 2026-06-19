<?php

namespace App\Livewire\Organizer;

use App\Models\Ticket;
use App\Services\TicketService;
use Livewire\Attributes\Layout;
use Livewire\Component;

class GlobalScanner extends Component
{
    public string $manualCode = '';

    public ?array $scanResult = null;

    public ?Ticket $lastTicket = null;

    public int $totalTicketsToday = 0;

    public int $checkedInToday = 0;

    public function mount()
    {
        $this->refreshStats();
    }

    public function refreshStats()
    {
        $organizerId = auth()->user()->organizer->id;

        $this->totalTicketsToday = Ticket::whereHas('event', function ($q) use ($organizerId) {
            $q->where('organizer_id', $organizerId)
                ->whereDate('start_date', '<=', now())
                ->whereDate('start_date', '>=', now()->subDays(1));
        })->count();

        $this->checkedInToday = Ticket::whereHas('event', function ($q) use ($organizerId) {
            $q->where('organizer_id', $organizerId);
        })->where('status', 'used')
            ->whereDate('scanned_at', now())
            ->count();
    }

    public function scan(?string $code = null)
    {
        $code = $code ?: $this->manualCode;

        if (! $code) {
            return;
        }

        $ticketService = app(TicketService::class);
        $result = $ticketService->validateAndCheckInGlobal(
            $code,
            auth()->user()->organizer->id,
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
        return view('livewire.organizer.global-scanner');
    }
}
