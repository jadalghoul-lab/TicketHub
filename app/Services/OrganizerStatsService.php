<?php

namespace App\Services;

use App\Models\Event;
use App\Models\Order;
use App\Models\Ticket;
use App\Models\Organizer;
use Carbon\Carbon;

class OrganizerStatsService
{
    /**
     * Get all dashboard stats for a specific organizer.
     */
    public function getDashboardStats(Organizer $organizer, string $period = 'month'): array
    {
        $dateFilter = $this->getDateFilter($period);
        $eventIds = Event::where('organizer_id', $organizer->id)->pluck('id');

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

        return [
            'totalRevenue' => $totalRevenue,
            'totalTickets' => $totalTickets,
            'attendanceRate' => $attendanceRate,
            'recentOrders' => $this->getRecentOrders($eventIds),
            'upcomingEvents' => $this->getUpcomingEvents($organizer->id),
            'topEvents' => $this->getTopEvents($organizer->id),
            'recentScans' => $this->getRecentScans($eventIds),
        ];
    }

    private function getDateFilter(string $period): \Carbon\CarbonInterface
    {
        return match($period) {
            'today' => now()->startOfDay(),
            'week' => now()->startOfWeek(),
            'month' => now()->startOfMonth(),
            default => now()->startOfMonth(),
        };
    }

    private function getRecentOrders($eventIds)
    {
        return Order::with(['user', 'event'])
            ->whereIn('event_id', $eventIds)
            ->where('status', 'paid')
            ->latest()
            ->take(5)
            ->get();
    }

    private function getUpcomingEvents(int $organizerId)
    {
        return Event::where('organizer_id', $organizerId)
            ->where('start_date', '>=', now())
            ->orderBy('start_date', 'asc')
            ->take(3)
            ->get();
    }

    private function getTopEvents(int $organizerId)
    {
        return Event::where('organizer_id', $organizerId)
            ->withCount(['tickets as tickets_count'])
            ->orderBy('tickets_count', 'desc')
            ->take(3)
            ->get();
    }

    private function getRecentScans($eventIds)
    {
        return Ticket::whereIn('event_id', $eventIds)
            ->where('status', 'used')
            ->with(['event', 'user'])
            ->orderBy('scanned_at', 'desc')
            ->take(5)
            ->get();
    }
}
