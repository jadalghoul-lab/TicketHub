<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Event;

class HomeController extends Controller
{
    public function index()
    {
        $trendingEvents = Event::published()
            ->with(['venue', 'organizer', 'ticketTypes'])
            ->withoutGlobalScopes() // public page — no tenant scope
            ->orderBy('start_date')
            ->limit(8)
            ->get();

        $upcomingEvents = Event::published()
            ->with(['venue', 'organizer', 'ticketTypes'])
            ->withoutGlobalScopes()
            ->where('start_date', '>=', now())
            ->orderBy('start_date')
            ->limit(4)
            ->get();

        return view('public.home', compact('trendingEvents', 'upcomingEvents'));
    }
}
