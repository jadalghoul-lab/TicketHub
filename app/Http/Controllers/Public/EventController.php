<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;

class EventController extends Controller
{
    /**
     * Public events listing page.
     */
    public function index(Request $request)
    {
        $query = Event::published()
            ->with(['venue', 'organizer', 'ticketTypes'])
            ->withoutGlobalScopes();

        // Search filter
        if ($request->search) {
            $query->where(function($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
            });
        }

        // Category filter (supports multiple)
        if ($request->category) {
            $query->whereIn('category', (array) $request->category);
        }

        // City filter
        if ($request->city) {
            $query->where('city', 'like', '%' . $request->city . '%');
        }

        // Date range filter
        if ($request->date_range === 'today') {
            $query->whereDate('start_date', now()->toDateString());
        } elseif ($request->date_range === 'weekend') {
            $query->whereBetween('start_date', [now()->startOfWeek()->addDays(5), now()->startOfWeek()->addDays(7)]);
        } elseif ($request->date_range === '30days') {
            $query->where('start_date', '<=', now()->addDays(30))->where('start_date', '>=', now());
        }

        // Free / Paid filter
        if ($request->type === 'free') {
            $query->whereHas('ticketTypes', fn($q) => $q->where('price', 0));
        } elseif ($request->type === 'paid') {
            $query->whereHas('ticketTypes', fn($q) => $q->where('price', '>', 0));
        }

        // Price filter
        if ($request->max_price && $request->max_price < 500) {
            $query->whereHas('ticketTypes', fn($q) => $q->where('price', '<=', $request->max_price));
        }

        // Available only (stock check)
        if ($request->available_only) {
            $query->whereHas('ticketTypes', fn($q) => $q->where('quantity', '>', 0));
        }

        // Sorting
        match ($request->sort) {
            'price_asc' => $query->join('ticket_types', 'events.id', '=', 'ticket_types.event_id')
                                 ->orderBy('ticket_types.price')->select('events.*'),
            'newest'    => $query->orderByDesc('events.created_at'),
            default     => $query->orderBy('start_date'),
        };

        $events = $query->paginate(12);

        return view('public.events.index', compact('events'));
    }

    /**
     * Public event detail page.
     */
    public function show(string $slug)
    {
        $event = Event::published()
            ->with(['venue', 'organizer', 'ticketTypes'])
            ->withoutGlobalScopes()
            ->where('slug', $slug)
            ->firstOrFail();

        return view('public.events.show', compact('event'));
    }

    /**
     * Handle successful checkout redirect.
     */
    public function checkoutSuccess(string $orderNumber)
    {
        $order = \App\Models\Order::where('order_number', $orderNumber)
            ->where('user_id', \Illuminate\Support\Facades\Auth::id())
            ->firstOrFail();

        return view('public.checkout.success', compact('order'));
    }

    /**
     * Handle cancelled checkout.
     */
    public function checkoutCancel(string $orderNumber)
    {
        $order = \App\Models\Order::where('order_number', $orderNumber)
            ->where('user_id', \Illuminate\Support\Facades\Auth::id())
            ->firstOrFail();

        // Redirect back to event page with info
        return redirect()->route('public.events.show', $order->items->first()->ticketType->event->slug)
            ->with('info', 'Your payment was cancelled. No charges were made.');
    }
}
