<?php

namespace App\Livewire\Organizer;

use App\Models\RefundRequest;
use App\Models\Order;
use App\Models\Ticket;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RefundManager extends Component
{
    public function approve($requestId)
    {
        $request = RefundRequest::with('order.event')->findOrFail($requestId);
        
        // Ensure the organizer owns this event
        if ($request->order->event->organizer_id !== Auth::user()->organizer->id) {
            return;
        }

        DB::transaction(function () use ($request) {
            // 1. Mark request as approved
            $request->update(['status' => 'approved']);

            // 2. Mark order as refunded
            $request->order->update(['status' => 'refunded']);

            // 3. Void all tickets for this order
            Ticket::where('order_id', $request->order_id)->update(['status' => 'refunded']);
        });

        session()->flash('success', 'Refund request approved. Order has been marked as refunded and tickets voided.');
    }

    public function reject($requestId, $reason = 'Refund criteria not met.')
    {
        $request = RefundRequest::with('order.event')->findOrFail($requestId);
        
        if ($request->order->event->organizer_id !== Auth::user()->organizer->id) {
            return;
        }

        $request->update(['status' => 'rejected']);
        
        session()->flash('info', 'Refund request rejected.');
    }

    public function render()
    {
        $organizerId = Auth::user()->organizer->id;
        
        $requests = RefundRequest::with(['order.event', 'user'])
            ->whereHas('order.event', function ($query) use ($organizerId) {
                $query->where('organizer_id', $organizerId);
            })
            ->latest()
            ->paginate(10);

        return view('livewire.organizer.refund-manager', [
            'requests' => $requests
        ]);
    }
}
