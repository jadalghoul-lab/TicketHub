<?php

namespace App\Http\Controllers\Organizer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\Order;
use App\Models\Ticket;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index(Request $request, \App\Services\OrganizerStatsService $statsService)
    {
        $organizer = Auth::user()->organizer;
        $period = $request->get('period', 'month');
        
        $stats = $statsService->getDashboardStats($organizer, $period);

        return view('organizer.dashboard', array_merge([
            'organizer' => $organizer,
            'period' => $period,
            'events' => Event::where('organizer_id', $organizer->id)->latest()->get(),
        ], $stats));
    }

}
