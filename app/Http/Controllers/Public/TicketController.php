<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Ticket;
use Illuminate\Support\Facades\Auth;

class TicketController extends Controller
{
    /**
     * Show the user's tickets.
     */
    public function index()
    {
        // For now, we fetch empty since we don't have checkout logic yet.
        // But we want to show the UI, so we can mock some data or just pass the variable.
        $tickets = Ticket::where('user_id', Auth::id())
            ->with(['order', 'ticketType.event.venue'])
            ->latest()
            ->paginate(10);

        return view('public.tickets.index', compact('tickets'));
    }

    /**
     * Show a specific ticket/QR code.
     */
    public function show(string $uuid)
    {
        $ticket = Ticket::where('uuid', $uuid)
            ->where('user_id', Auth::id())
            ->with(['order', 'ticketType.event.venue'])
            ->firstOrFail();

        return view('public.tickets.show', compact('ticket'));
    }
}
