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
        $event = Event::withoutGlobalScopes()
            ->withTrashed()
            ->with(['venue', 'organizer', 'ticketTypes'])
            ->where('slug', $slug)
            ->firstOrFail();

        $user = auth()->user();
        
        // Robust Admin check
        $isAdmin = false;
        if ($user) {
            $isAdmin = $user->role === \App\Enums\Role::ADMIN || $user->role->value === 'admin' || (method_exists($user, 'isAdmin') && $user->isAdmin());
        }

        // Owner check
        $isOwner = false;
        if ($user && $user->organizer) {
            $isOwner = $user->organizer->id === $event->organizer_id;
        }

        // Publicly visible only if published and NOT trashed
        $isPubliclyVisible = $event->status === \App\Enums\EventStatus::PUBLISHED && !$event->trashed();

        if (!$isPubliclyVisible && !$isAdmin && !$isOwner) {
            // Log for debugging if it's the specific slug the user mentioned
            if ($slug === 'food-wine-festival-223') {
                \Log::info("Access denied for food-wine-festival-223. User: " . ($user ? $user->email : 'Guest') . " Admin: " . ($isAdmin ? 'Yes' : 'No'));
            }
            abort(404);
        }

        return view('public.events.show', compact('event'));
    }

    /**
     * Handle successful checkout redirect.
     */
    public function checkoutSuccess(string $orderNumber, \App\Services\CheckoutService $checkoutService)
    {
        $order = \App\Models\Order::where('order_number', $orderNumber)
            ->where('user_id', \Illuminate\Support\Facades\Auth::id())
            ->firstOrFail();

        // Real-time Verification for Test Mode (Retrieves actual Stripe session)
        if ($order->status === 'pending' && $order->payment_intent_id && !str_starts_with($order->payment_intent_id, 'pi_mock_')) {
            try {
                \Stripe\Stripe::setApiKey(config('services.stripe.secret'));
                $session = \Stripe\Checkout\Session::retrieve($order->payment_intent_id);
                
                if ($session->payment_status === 'paid') {
                    $checkoutService->fulfillOrder($session);
                    $order->refresh();
                }
            } catch (\Exception $e) {
                \Log::error("Stripe Verification Error: " . $e->getMessage());
            }
        }

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
