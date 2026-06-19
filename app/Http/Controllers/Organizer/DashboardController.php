<?php

namespace App\Http\Controllers\Organizer;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Order;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $organizer = Auth::user()->organizer;
        $events = Event::where('organizer_id', $organizer->id)->latest()->get();
        $period = $request->get('period', 'month');

        $dateFilter = match ($period) {
            'today' => now()->startOfDay(),
            'week' => now()->startOfWeek(),
            'month' => now()->startOfMonth(),
            default => now()->startOfMonth(),
        };

        // Base query for organizer's events
        $eventIds = Event::where('organizer_id', $organizer->id)->pluck('id');

        // Stats
        $totalRevenue = Order::whereIn('event_id', $eventIds)
            ->where('status', 'paid')
            ->where('created_at', '>=', $dateFilter)
            ->sum('total_amount');

        $totalTickets = Ticket::whereIn('event_id', $eventIds)
            ->where('created_at', '>=', $dateFilter)
            ->count();

        $scannedTickets = Ticket::whereIn('event_id', $eventIds)
            ->where('status', 'used')
            ->where('created_at', '>=', $dateFilter)
            ->count();

        $attendanceRate = $totalTickets > 0 ? round(($scannedTickets / $totalTickets) * 100) : 0;

        // Recent Orders
        $recentOrders = Order::with(['user', 'event'])
            ->whereIn('event_id', $eventIds)
            ->where('status', 'paid')
            ->latest()
            ->take(5)
            ->get();

        // Upcoming Events
        $upcomingEvents = Event::where('organizer_id', $organizer->id)
            ->where('start_date', '>=', now())
            ->orderBy('start_date', 'asc')
            ->take(3)
            ->get();

        // Top Events
        $topEvents = Event::where('organizer_id', $organizer->id)
            ->withCount(['tickets as tickets_count'])
            ->orderBy('tickets_count', 'desc')
            ->take(3)
            ->get();

        // Recent Scans
        $recentScans = Ticket::whereIn('event_id', $eventIds)
            ->where('status', 'used')
            ->with(['event', 'user'])
            ->orderBy('scanned_at', 'desc')
            ->take(5)
            ->get();

        return view('organizer.dashboard', compact(
            'organizer', 'events', 'totalRevenue', 'totalTickets',
            'attendanceRate', 'recentOrders', 'upcomingEvents', 'topEvents', 'period', 'recentScans'
        ));
    }
}
