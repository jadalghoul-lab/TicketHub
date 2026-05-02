<x-mail::message>
# Your Tickets are Ready!

Hi {{ $order->user->name }},

Get ready for an amazing experience! Your order **#{{ $order->order_number }}** has been processed successfully. Below you will find your tickets for **{{ $event->title }}**.

<x-mail::panel>
**Event Details**  
**Date:** {{ $event->start_date->format('l, d M Y') }}  
**Time:** {{ $event->time ? \Carbon\Carbon::parse($event->time)->format('H:i') : 'TBA' }}  
**Venue:** {{ $event->venue?->name ?? $event->city }}
</x-mail::panel>

## Your Digital Passes

@foreach($tickets as $ticket)
<div style="background-color: #f8fafc; border: 1px solid #e2e8f0; border-radius: 16px; padding: 20px; margin-bottom: 20px; text-align: center;">
    <p style="font-size: 10px; font-weight: bold; color: #64748b; text-transform: uppercase; letter-spacing: 0.1em; margin-bottom: 5px;">{{ $ticket['type'] }} Admission</p>
    <h3 style="margin-top: 0; color: #0f172a;">Ticket #{{ $ticket['number'] }}</h3>
    
    <img src="{{ $ticket['qr_base64'] }}" width="150" height="150" style="margin: 15px auto; display: block;" alt="QR Code">
    
    <p style="font-size: 12px; color: #4f46e5; font-weight: bold;">{{ $ticket['uuid'] }}</p>
</div>
@endforeach

<x-mail::button :url="route('public.tickets.index')">
View in My Collection
</x-mail::button>

### Order Summary
* **Total Amount:** €{{ number_format($order->total_amount, 2) }}
* **Payment Status:** Paid
* **Purchase Date:** {{ $order->created_at->format('d M Y') }}

Please have these QR codes ready at the entrance. Each ticket is unique and can only be scanned once.

Thanks,<br>
The {{ config('app.name') }} Team
</x-mail::message>
