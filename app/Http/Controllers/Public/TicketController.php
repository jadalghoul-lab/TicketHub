<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Ticket;
use Illuminate\Support\Facades\Auth;
use App\Services\TicketService;

class TicketController extends Controller
{
    /**
     * Show the user's tickets.
     */
    public function index()
    {
        $tickets = Ticket::where('user_id', Auth::id())
            ->with(['order', 'ticketType.event.venue'])
            ->latest()
            ->paginate(10);

        return view('public.tickets.index', compact('tickets'));
    }

    /**
     * Show a specific ticket/QR code.
     */
    public function show(string $uuid, TicketService $ticketService)
    {
        $ticket = Ticket::where('uuid', $uuid)
            ->where('user_id', Auth::id())
            ->with(['order', 'ticketType.event.venue'])
            ->firstOrFail();

        $qrCode = $ticketService->generateQrCode($ticket);

        return view('public.tickets.show', compact('ticket', 'qrCode'));
    }
}
