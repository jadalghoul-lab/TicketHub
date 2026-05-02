<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Order;
use App\Models\Organizer;
use App\Models\Ticket;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $totalSales = Order::where('status', 'paid')->sum('total_amount');
        $totalTicketsSold = Ticket::where('status', 'valid')->count();
        $activeEventsCount = Event::published()->count();
        $totalOrganizersCount = Organizer::count();

        $recentOrders = Order::with(['user', 'event'])
            ->where('status', 'paid')
            ->latest()
            ->take(5)
            ->get();

        $upcomingEvents = Event::published()
            ->with('organizer')
            ->where('start_date', '>=', now())
            ->orderBy('start_date', 'asc')
            ->take(5)
            ->get();

        $newOrganizers = Organizer::with('user')
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalSales',
            'totalTicketsSold',
            'activeEventsCount',
            'totalOrganizersCount',
            'recentOrders',
            'upcomingEvents',
            'newOrganizers'
        ));
    }

    public function export()
    {
        $fileName = 'ticket_hub_sales_report_' . now()->format('Y-m-d_His') . '.csv';
        $orders = Order::with(['user', 'event'])->where('status', 'paid')->latest()->get();

        $headers = array(
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        );

        $columns = array('Order Number', 'Customer', 'Email', 'Event', 'Amount', 'Date');

        $callback = function() use($orders, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($orders as $order) {
                $row['Order Number'] = $order->order_number;
                $row['Customer']     = $order->user->name;
                $row['Email']        = $order->user->email;
                $row['Event']        = $order->event?->title ?? 'Deleted Event';
                $row['Amount']       = '€' . number_format($order->total_amount, 2);
                $row['Date']         = $order->created_at->format('Y-m-d H:i');

                fputcsv($file, array(
                    $row['Order Number'], 
                    $row['Customer'], 
                    $row['Email'], 
                    $row['Event'], 
                    $row['Amount'], 
                    $row['Date']
                ));
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
