@extends('layouts.public')

@section('content')
<style>
@media print {
    /* Aggressive approach: Hide EVERYTHING except the ticket card */
    body * { 
        visibility: hidden !important; 
    }
    
    .ticket-card, .ticket-card * { 
        visibility: visible !important; 
    }

    /* Absolute positioning to move the card to the very top left */
    .ticket-card {
        position: absolute !important;
        left: 0 !important;
        top: 0 !important;
        width: 100% !important;
        border: none !important;
        box-shadow: none !important;
        margin: 0 !important;
        padding: 0 !important;
    }

    /* Hide non-essential parts of the card during print */
    .no-print-essential, .details-grid, .security-disclaimer, .no-print {
        display: none !important;
        height: 0 !important;
        margin: 0 !important;
        padding: 0 !important;
    }
    
    /* Page settings to remove browser headers/footers */
    @page {
        size: auto;
        margin: 0mm;
    }

    /* Container fixes */
    .min-h-screen, .max-w-2xl {
        padding: 0 !important;
        margin: 0 !important;
        min-height: 0 !important;
    }

    /* Print Logo Header */
    .print-header {
        display: flex !important;
        justify-content: space-between;
        align-items: center;
        padding: 2rem;
        border-bottom: 2px dashed #f1f5f9;
    }

    .print-logo {
        font-size: 28px !important;
        font-weight: 900 !important;
        color: #4f46e5 !important;
        letter-spacing: -1px;
    }
}

/* Hide print header on screen */
.print-header { display: none; }
</style>

<div class="min-h-screen bg-[#f8fafc] py-12 px-4 flex items-center justify-center">
    <div class="max-w-2xl w-full">
        <!-- Back Link -->
        <a href="{{ route('public.tickets.index') }}" class="inline-flex items-center gap-2 text-slate-500 hover:text-indigo-600 transition-colors mb-8 font-bold group no-print">
            <span class="material-symbols-outlined transition-transform group-hover:-translate-x-1">arrow_back</span>
            Back to Collection
        </a>

        <!-- Modern Ticket Card -->
        <div class="bg-white rounded-[3rem] shadow-2xl shadow-indigo-200/40 border border-slate-100 overflow-hidden relative group ticket-card">
            
            <!-- Print Only Header -->
            <div class="print-header">
                <div class="print-logo">TicketHub</div>
                <div class="text-[10px] font-black uppercase tracking-widest text-slate-400">Official Admission Voucher</div>
            </div>

            <!-- Decorative Notches (Screen Only) -->
            <div class="absolute left-0 top-1/2 -translate-y-1/2 -translate-x-1/2 w-12 h-12 bg-[#f8fafc] rounded-full border border-slate-100 z-10 no-print"></div>
            <div class="absolute right-0 top-1/2 -translate-y-1/2 translate-x-1/2 w-12 h-12 bg-[#f8fafc] rounded-full border border-slate-100 z-10 no-print"></div>

            <!-- Ticket Header / Event Info -->
            <div class="p-10 border-b-2 border-dashed border-slate-100 relative">
                <div class="flex justify-between items-start mb-6 no-print-essential">
                    <span class="bg-indigo-600 text-white text-[10px] font-black uppercase tracking-[0.2em] px-4 py-2 rounded-xl shadow-lg shadow-indigo-100">
                        Admission Ticket
                    </span>
                    <div class="text-right">
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Ticket ID</p>
                        <p class="text-xs font-mono font-bold text-slate-900">#{{ substr($ticket->uuid, 0, 8) }}</p>
                    </div>
                </div>

                <h1 class="text-3xl font-black text-slate-900 leading-tight mb-2 group-hover:text-indigo-600 transition-colors">
                    {{ $ticket->ticketType->event->title }}
                </h1>
                
                <div class="flex flex-wrap items-center gap-y-2 gap-x-6 text-slate-500 font-medium text-sm">
                    <div class="flex items-center gap-1.5">
                        <span class="material-symbols-outlined text-indigo-500 text-[18px]">calendar_today</span>
                        {{ $ticket->ticketType->event->start_date->format('l, M d, Y') }}
                    </div>
                    <div class="flex items-center gap-1.5">
                        <span class="material-symbols-outlined text-indigo-500 text-[18px]">location_on</span>
                        {{ $ticket->ticketType->event->city }}
                    </div>
                    <div class="flex items-center gap-1.5">
                        <span class="material-symbols-outlined text-indigo-500 text-[18px]">schedule</span>
                        {{ \Carbon\Carbon::parse($ticket->ticketType->event->time)->format('g:i A') }}
                    </div>
                </div>
            </div>

            <!-- QR Code Section -->
            <div class="p-12 qr-container flex flex-col items-center text-center">
                <div class="relative mb-10">
                    <div class="absolute -inset-8 bg-indigo-50 rounded-[3rem] blur-2xl opacity-50 group-hover:opacity-100 transition-opacity duration-700 no-print"></div>
                    <div class="relative bg-white p-8 rounded-[2.5rem] border-2 border-slate-50 shadow-xl shadow-indigo-100/50 qr-box">
                        <div class="w-64 h-64 flex items-center justify-center">
                            {!! $qrCode !!}
                        </div>
                    </div>
                </div>

                <div class="space-y-3 mb-10">
                    <p class="text-[11px] font-black text-slate-400 uppercase tracking-[0.4em]">Unique Entry Code</p>
                    <h2 class="text-4xl font-black text-slate-900 tracking-tighter">{{ $ticket->ticket_number }}</h2>
                    <div class="flex items-center justify-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-green-500 animate-pulse no-print"></span>
                        <p class="text-[11px] font-black text-green-600 uppercase tracking-widest">Valid & Verified Admission</p>
                    </div>
                </div>

                <!-- Details Grid -->
                <div class="grid grid-cols-2 gap-8 w-full p-8 bg-slate-50 rounded-[2rem] border border-slate-100/50 details-grid">
                    <div class="text-left border-r border-slate-200 pr-4">
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5">Admission Type</p>
                        <p class="text-sm font-bold text-slate-900">{{ $ticket->ticketType->name }}</p>
                    </div>
                    <div class="text-left pl-4">
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5">Ticket Holder</p>
                        <p class="text-sm font-bold text-slate-900">{{ Auth::user()->name }}</p>
                    </div>
                </div>
            </div>

            <!-- Print Actions (Screen Only) -->
            <div class="px-10 py-8 bg-slate-50 border-t border-slate-100 flex flex-col gap-4 no-print">
                <button onclick="window.print()" class="w-full bg-white text-slate-900 border border-slate-200 px-6 py-4 rounded-2xl font-bold hover:bg-slate-100 transition-all flex items-center justify-center gap-3 shadow-sm active:scale-95">
                    <span class="material-symbols-outlined">print</span>
                    Print Official Ticket
                </button>
                
                @livewire('public.refund-request-form', ['order' => $ticket->order])
            </div>
        </div>

        <!-- Security Disclaimer -->
        <p class="mt-8 text-center text-slate-400 text-xs font-medium px-8 leading-relaxed security-disclaimer">
            This ticket is protected by digital encryption. Any attempt to replicate or alter this ticket will invalidate its access code. 
            Please present this voucher at the venue entrance.
        </p>
    </div>
</div>
@endsection
