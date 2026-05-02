@extends('layouts.public')

@section('content')
<main class="flex-grow max-w-screen-md mx-auto w-full px-6 py-12">
    <div class="bg-white rounded-2xl border border-outline-variant shadow-lg overflow-hidden">
        <div class="bg-surface-container-low p-6 border-b border-outline-variant flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold text-slate-900">{{ $ticket->ticketType->event->title }}</h1>
                <p class="text-sm text-slate-500">{{ $ticket->ticketType->name }} Admission</p>
            </div>
            <a href="{{ route('public.tickets.index') }}" class="text-slate-500 hover:text-slate-900">
                <span class="material-symbols-outlined">close</span>
            </a>
        </div>

        <div class="p-10 flex flex-col items-center text-center">
            <!-- QR Code Placeholder -->
            <div class="w-64 h-64 bg-white border-2 border-slate-100 rounded-3xl p-6 mb-8 shadow-inner flex items-center justify-center">
                <!-- Using a placeholder QR image or generating one -->
                <img src="https://api.qrserver.com/v1/create-qr-code/?size=200x200&data={{ $ticket->uuid ?? $ticket->id }}" 
                     alt="Ticket QR Code" class="w-full h-full" />
            </div>

            <div class="space-y-2 mb-8">
                <h2 class="text-3xl font-bold text-slate-900 tracking-tight">{{ $ticket->uuid ?? 'ORD-'.$ticket->id }}</h2>
                <p class="text-sm font-semibold text-primary uppercase tracking-widest">Authorized Admission</p>
            </div>

            <div class="grid grid-cols-2 gap-8 w-full py-8 border-y border-slate-100">
                <div class="text-left">
                    <p class="text-[10px] font-bold text-outline uppercase mb-1">Date & Time</p>
                    <p class="text-sm font-bold text-slate-900">{{ $ticket->ticketType->event->start_date->format('l, M d') }}</p>
                    <p class="text-sm text-slate-500">{{ $ticket->ticketType->event->time ? \Carbon\Carbon::parse($ticket->ticketType->event->time)->format('g:i A') : 'TBA' }}</p>
                </div>
                <div class="text-left">
                    <p class="text-[10px] font-bold text-outline uppercase mb-1">Location</p>
                    <p class="text-sm font-bold text-slate-900">{{ $ticket->ticketType->event->venue?->name ?? 'TBA' }}</p>
                    <p class="text-sm text-slate-500">{{ $ticket->ticketType->event->city }}</p>
                </div>
            </div>

            <div class="mt-8 w-full">
                <div class="flex items-center gap-4 p-4 bg-surface-container-low rounded-xl text-left">
                    <div class="w-12 h-12 rounded-lg bg-white flex items-center justify-center shadow-sm">
                        <span class="material-symbols-outlined text-primary">info</span>
                    </div>
                    <p class="text-xs text-on-surface-variant leading-relaxed">
                        Please present this QR code at the entrance. Each ticket is unique and can only be scanned once. Do not share this code.
                    </p>
                </div>
            </div>
        </div>

        <div class="bg-slate-50 p-6 border-t border-outline-variant flex justify-center gap-4">
            <button onclick="window.print()" class="bg-white text-on-surface border border-outline-variant px-6 py-2 rounded-lg font-semibold hover:bg-slate-100 transition-colors flex items-center gap-2">
                <span class="material-symbols-outlined text-sm">print</span>
                Print Ticket
            </button>
            <button class="bg-primary text-white px-6 py-2 rounded-lg font-semibold hover:bg-primary/90 transition-all flex items-center gap-2">
                <span class="material-symbols-outlined text-sm">save_alt</span>
                Save to Phone
            </button>
        </div>
    </div>
</main>

<style>
@media print {
    header, footer, .bg-slate-50 { display: none !important; }
    main { padding: 0 !important; }
    .bg-white { border: none !important; shadow: none !important; }
}
</style>
@endsection
