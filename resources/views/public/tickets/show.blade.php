@extends('layouts.public')

@section('content')
<div class="min-h-screen bg-[#f8fafc] py-12 px-4 flex items-center justify-center">
    <div class="max-w-2xl w-full">
        <!-- Back Link -->
        <a href="{{ route('public.tickets.index') }}" class="inline-flex items-center gap-2 text-slate-500 hover:text-indigo-600 transition-colors mb-8 font-bold group">
            <span class="material-symbols-outlined transition-transform group-hover:-translate-x-1">arrow_back</span>
            Back to Collection
        </a>

        <!-- Modern Ticket Card -->
        <div class="bg-white rounded-[3rem] shadow-2xl shadow-indigo-200/40 border border-slate-100 overflow-hidden relative group">
            <!-- Decorative Notches -->
            <div class="absolute left-0 top-1/2 -translate-y-1/2 -translate-x-1/2 w-12 h-12 bg-[#f8fafc] rounded-full border border-slate-100 z-10"></div>
            <div class="absolute right-0 top-1/2 -translate-y-1/2 translate-x-1/2 w-12 h-12 bg-[#f8fafc] rounded-full border border-slate-100 z-10"></div>

            <!-- Ticket Header / Event Info -->
            <div class="p-10 border-b-2 border-dashed border-slate-100 relative">
                <div class="flex justify-between items-start mb-6">
                    <span class="bg-indigo-600 text-white text-[10px] font-black uppercase tracking-[0.2em] px-4 py-2 rounded-xl shadow-lg shadow-indigo-100">
                        Official Admission
                    </span>
                    <div class="text-right">
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Ticket ID</p>
                        <p class="text-xs font-mono font-bold text-slate-900">#{{ substr($ticket->uuid, 0, 8) }}</p>
                    </div>
                </div>

                <h1 class="text-3xl font-black text-slate-900 leading-tight mb-2 group-hover:text-indigo-600 transition-colors">
                    {{ $ticket->ticketType->event->title }}
                </h1>
                <div class="flex items-center gap-6 text-slate-500 font-medium text-sm">
                    <div class="flex items-center gap-1.5">
                        <span class="material-symbols-outlined text-indigo-500 text-[18px]">calendar_today</span>
                        {{ $ticket->ticketType->event->start_date->format('l, M d, Y') }}
                    </div>
                    <div class="flex items-center gap-1.5">
                        <span class="material-symbols-outlined text-indigo-500 text-[18px]">location_on</span>
                        {{ $ticket->ticketType->event->city }}
                    </div>
                </div>
            </div>

            <!-- QR Code Section -->
            <div class="p-12 flex flex-col items-center text-center">
                <div class="relative mb-10">
                    <div class="absolute -inset-8 bg-indigo-50 rounded-[3rem] blur-2xl opacity-50 group-hover:opacity-100 transition-opacity duration-700"></div>
                    <div class="relative bg-white p-8 rounded-[2.5rem] border-2 border-slate-50 shadow-xl shadow-indigo-100/50">
                        <div class="w-64 h-64 flex items-center justify-center">
                            {!! $qrCode !!}
                        </div>
                    </div>
                </div>

                <div class="space-y-3 mb-10">
                    <p class="text-[11px] font-black text-slate-400 uppercase tracking-[0.4em]">Unique Entry Code</p>
                    <h2 class="text-4xl font-black text-slate-900 tracking-tighter">{{ $ticket->ticket_number }}</h2>
                    <div class="flex items-center justify-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-green-500 animate-pulse"></span>
                        <p class="text-[11px] font-black text-green-600 uppercase tracking-widest">Valid & Verified</p>
                    </div>
                </div>

                <!-- Footer / Details Grid -->
                <div class="grid grid-cols-2 gap-8 w-full p-8 bg-slate-50 rounded-[2rem] border border-slate-100/50">
                    <div class="text-left border-r border-slate-200 pr-4">
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5">Admission Type</p>
                        <p class="text-sm font-bold text-slate-900">{{ $ticket->ticketType->name }} Pass</p>
                        <p class="text-[11px] text-slate-500 mt-1">Single Entry Only</p>
                    </div>
                    <div class="text-left pl-4">
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5">Holder Name</p>
                        <p class="text-sm font-bold text-slate-900">{{ Auth::user()->name }}</p>
                        <p class="text-[11px] text-slate-500 mt-1">Non-Transferable</p>
                    </div>
                </div>
            </div>

            <!-- Print Actions -->
            <div class="px-10 py-8 bg-slate-50 border-t border-slate-100 flex justify-center gap-4 no-print">
                <button onclick="window.print()" class="flex-grow max-w-[200px] bg-white text-slate-900 border border-slate-200 px-6 py-4 rounded-2xl font-bold hover:bg-slate-100 transition-all flex items-center justify-center gap-3 shadow-sm active:scale-95">
                    <span class="material-symbols-outlined">print</span>
                    Print Ticket
                </button>
            </div>
        </div>

        <!-- Security Disclaimer -->
        <p class="mt-8 text-center text-slate-400 text-xs font-medium px-8 leading-relaxed">
            This ticket is protected by encryption. Any attempt to replicate or alter this ticket will invalidate its access code. 
            Please have this screen ready at the checkpoint.
        </p>
    </div>
</div>

<style>
@media print {
    header, footer, .no-print, .bg-[#f8fafc] { display: none !important; }
    body { background: white !important; }
    .max-w-2xl { max-width: 100% !important; margin: 0 !important; }
    .shadow-2xl, .shadow-xl { box-shadow: none !important; }
    .border { border: 1px solid #e2e8f0 !important; }
    .rounded-[3rem] { border-radius: 1.5rem !important; }
    .bg-white { background: white !important; }
}
</style>
@endsection
