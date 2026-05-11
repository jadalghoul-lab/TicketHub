@extends('layouts.public')

@section('content')
<div class="min-h-screen bg-[#f8fafc] py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-7xl mx-auto">
        <!-- Header Section -->
        <div class="flex flex-col md:flex-row md:items-end justify-between mb-12 gap-6">
            <div>
                <h1 class="text-4xl font-black text-slate-900 tracking-tight mb-2">My Collection</h1>
                <p class="text-slate-500 font-medium text-lg">Your entry passes for upcoming and past experiences.</p>
            </div>
            <div class="flex items-center gap-4 bg-white p-2 rounded-2xl shadow-sm border border-slate-100">
                <div class="px-4 py-2 text-center border-r border-slate-100">
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Total Tickets</p>
                    <p class="text-xl font-black text-slate-900">{{ $tickets->total() }}</p>
                </div>
                <div class="px-4 py-2 text-center">
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Active Passes</p>
                    <p class="text-xl font-black text-indigo-600">{{ $tickets->where('status', 'valid')->count() }}</p>
                </div>
            </div>
        </div>

        @if($tickets->isEmpty())
            <div class="bg-white rounded-[2.5rem] p-16 text-center shadow-xl shadow-slate-200/50 border border-slate-100">
                <div class="w-24 h-24 bg-indigo-50 rounded-full flex items-center justify-center mx-auto mb-6">
                    <span class="material-symbols-outlined text-4xl text-indigo-600">confirmation_number</span>
                </div>
                <h2 class="text-2xl font-bold text-slate-900 mb-2">No tickets found yet</h2>
                <p class="text-slate-500 max-w-sm mx-auto mb-8 text-lg">It looks like you haven't booked any experiences. Explore our marketplace to find your next adventure!</p>
                <a href="{{ route('home') }}" class="inline-flex items-center gap-2 bg-indigo-600 text-white px-8 py-4 rounded-2xl font-bold hover:bg-indigo-700 transition-all shadow-lg shadow-indigo-200 hover:-translate-y-1">
                    Browse Events
                    <span class="material-symbols-outlined text-[20px]">arrow_forward</span>
                </a>
            </div>
        @else
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                @foreach($tickets as $ticket)
                    <div class="group bg-white rounded-[2.5rem] overflow-hidden shadow-xl shadow-slate-200/40 border border-slate-100 hover:shadow-2xl hover:shadow-indigo-100/50 transition-all duration-500 hover:-translate-y-1">
                        <div class="flex flex-col sm:flex-row h-full">
                            <!-- Event Image -->
                            <div class="sm:w-48 h-48 sm:h-auto relative overflow-hidden">
                                @php
                                    $event = $ticket->event ?? $ticket->ticketType?->event;
                                @endphp
                                <img src="{{ $event?->image_url }}" 
                                     class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110" 
                                     alt="{{ $event?->title ?? 'Event' }}">
                                <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
                                <div class="absolute bottom-4 left-4">
                                    <span class="bg-white/20 backdrop-blur-md text-white text-[10px] font-bold px-2 py-1 rounded-lg border border-white/30 uppercase tracking-widest">
                                        {{ $ticket->ticketType?->name ?? 'Standard' }}
                                    </span>
                                </div>
                            </div>

                            <!-- Content -->
                            <div class="flex-grow p-8 flex flex-col">
                                <div class="flex justify-between items-start mb-4">
                                    <div>
                                        <h3 class="text-xl font-black text-slate-900 leading-tight group-hover:text-indigo-600 transition-colors mb-1">
                                            {{ $event?->title ?? 'Unknown Event' }}
                                        </h3>
                                        <p class="text-slate-500 font-medium text-sm flex items-center gap-1">
                                            <span class="material-symbols-outlined text-[16px]">location_on</span>
                                            {{ $event?->city ?? 'Unknown Location' }}
                                        </p>
                                    </div>
                                    <div @class([
                                        'px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest shadow-sm',
                                        'bg-green-50 text-green-600 border border-green-100' => $ticket->status === 'valid',
                                        'bg-slate-50 text-slate-400 border border-slate-100' => $ticket->status !== 'valid',
                                    ])>
                                        {{ $ticket->status }}
                                    </div>
                                </div>

                                <div class="grid grid-cols-2 gap-4 mb-8">
                                    <div>
                                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Date</p>
                                        <p class="text-sm font-bold text-slate-800">{{ $event?->start_date ? $event->start_date->format('M d, Y') : 'TBA' }}</p>
                                    </div>
                                    <div>
                                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Pass Code</p>
                                        <p class="text-sm font-mono font-bold text-slate-800 tracking-tight">#{{ substr($ticket->uuid, 0, 8) }}</p>
                                    </div>
                                </div>

                                <div class="mt-auto pt-6 border-t border-slate-50 flex items-center justify-between">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-full bg-slate-50 flex items-center justify-center border border-slate-100">
                                            <span class="material-symbols-outlined text-slate-400 text-[20px]">confirmation_number</span>
                                        </div>
                                        <div>
                                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Order Ref</p>
                                            <p class="text-xs font-bold text-slate-800">#{{ $ticket->order->order_number }}</p>
                                        </div>
                                    </div>
                                    <div class="flex gap-2">
                                        <a href="{{ route('public.tickets.pdf', $ticket->id) }}" 
                                           class="bg-white text-indigo-600 border border-indigo-100 w-12 h-12 rounded-2xl flex items-center justify-center hover:bg-indigo-50 transition-all shadow-sm group-hover:scale-105" title="Download PDF">
                                            <span class="material-symbols-outlined">picture_as_pdf</span>
                                        </a>
                                        <a href="{{ route('public.tickets.show', $ticket->uuid) }}" 
                                           class="bg-indigo-600 text-white w-12 h-12 rounded-2xl flex items-center justify-center hover:bg-indigo-700 transition-all shadow-lg shadow-indigo-100 group-hover:scale-105" title="View Ticket">
                                            <span class="material-symbols-outlined">arrow_forward</span>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-12">
                {{ $tickets->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
