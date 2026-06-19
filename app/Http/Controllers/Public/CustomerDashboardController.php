<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Ticket;
use Illuminate\Support\Facades\Auth;

class CustomerDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $totalTickets = Ticket::where('user_id', $user->id)->count();

        $recentOrders = Order::with('event')
            ->where('user_id', $user->id)
            ->latest()
            ->take(5)
            ->get();

        $upcomingTickets = Ticket::with(['event', 'ticketType'])
            ->where('tickets.user_id', $user->id)
            ->where('tickets.status', 'valid')
            ->join('events', 'tickets.event_id', '=', 'events.id')
            ->where('events.start_date', '>=', now())
            ->orderBy('events.start_date', 'asc')
            ->select('tickets.*')
            ->take(3)
            ->get();

        return view('dashboard', compact('user', 'totalTickets', 'recentOrders', 'upcomingTickets'));
    }
}
