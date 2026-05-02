@extends('layouts.public')

@section('content')
<main class="flex-grow max-w-screen-xl mx-auto w-full px-6 py-12">
    <div class="mb-10">
        <h1 class="text-4xl font-bold text-slate-900 mb-2">My Tickets</h1>
        <p class="text-slate-500">Manage and view all your event admissions</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-12">
        <!-- Main Ticket List -->
        <div class="lg:col-span-8 space-y-6">
            @if($tickets->count() > 0)
                @foreach($tickets as $ticket)
                <div class="bg-white rounded-xl border border-outline-variant overflow-hidden shadow-sm hover:shadow-md transition-all flex flex-col sm:flex-row">
                    <!-- Event Image (Left) -->
                    <div class="w-full sm:w-48 h-48 sm:h-auto overflow-hidden flex-shrink-0 relative">
                        <img class="w-full h-full object-cover" 
                             src="{{ $ticket->ticketType->event->image ? Storage::url($ticket->ticketType->event->image) : 'https://images.unsplash.com/photo-1501281668745-f7f57925c3b4?q=80&w=800' }}" 
                             alt="{{ $ticket->ticketType->event->title }}" />
                        <div class="absolute top-0 left-0 w-1.5 h-full bg-primary"></div>
                    </div>

                    <!-- Ticket Content -->
                    <div class="p-6 flex-grow flex flex-col justify-between">
                        <div>
                            <div class="flex justify-between items-start mb-2">
                                <h3 class="text-xl font-bold text-slate-900">{{ $ticket->ticketType->event->title }}</h3>
                                <span class="bg-indigo-50 text-primary text-[10px] font-bold uppercase tracking-wider px-2 py-0.5 rounded">
                                    {{ $ticket->ticketType->name }}
                                </span>
                            </div>
                            
                            <div class="flex flex-wrap gap-x-6 gap-y-2 text-sm text-on-surface-variant">
                                <p class="flex items-center gap-1.5">
                                    <span class="material-symbols-outlined text-sm">calendar_today</span>
                                    {{ $ticket->ticketType->event->start_date->format('M d, Y') }}
                                </p>
                                <p class="flex items-center gap-1.5">
                                    <span class="material-symbols-outlined text-sm">location_on</span>
                                    {{ $ticket->ticketType->event->venue?->name ?? $ticket->ticketType->event->city }}
                                </p>
                                <p class="flex items-center gap-1.5 font-semibold text-primary">
                                    <span class="material-symbols-outlined text-sm">confirmation_number</span>
                                    #{{ $ticket->uuid ?? 'ORD-'.$ticket->id }}
                                </p>
                            </div>
                        </div>

                        <div class="mt-6 flex items-center justify-between pt-4 border-t border-slate-100">
                            <div class="flex items-center gap-2">
                                <span class="material-symbols-outlined text-green-600">verified</span>
                                <span class="text-xs font-semibold text-green-600 uppercase">Valid Admission</span>
                            </div>
                            <div class="flex gap-3">
                                <a href="{{ route('public.tickets.show', $ticket->uuid ?? $ticket->id) }}" 
                                   class="bg-surface-container-low text-on-surface px-4 py-2 rounded-lg text-sm font-semibold hover:bg-slate-200 transition-colors flex items-center gap-2">
                                    <span class="material-symbols-outlined text-sm">qr_code_2</span>
                                    View Ticket
                                </a>
                                <button class="bg-primary text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-primary/90 active:scale-95 transition-all flex items-center gap-2">
                                    <span class="material-symbols-outlined text-sm">download</span>
                                    PDF
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach

                <div class="mt-8">
                    {{ $tickets->links('vendor.pagination.simple-custom') }}
                </div>
            @else
                <!-- Empty State based on the design's clean aesthetic -->
                <div class="bg-white rounded-xl border border-outline-variant p-16 text-center shadow-sm">
                    <div class="w-20 h-20 bg-surface-container-low rounded-full flex items-center justify-center mx-auto mb-6">
                        <span class="material-symbols-outlined text-slate-300 text-4xl">confirmation_number</span>
                    </div>
                    <h2 class="text-2xl font-bold text-slate-900 mb-2">No tickets found</h2>
                    <p class="text-slate-500 mb-8 max-w-md mx-auto">You haven't purchased any tickets yet. Explore our marketplace to find your next favorite event.</p>
                    <a href="{{ route('public.events.index') }}" 
                       class="bg-primary text-white px-8 py-3 rounded-xl font-semibold shadow-lg shadow-primary/20 hover:bg-primary/90 transition-all active:scale-95 inline-flex items-center gap-2">
                        Browse Events
                        <span class="material-symbols-outlined text-sm">arrow_forward</span>
                    </a>
                </div>
            @endif
        </div>

        <!-- Sidebar (Stats/Quick Links) -->
        <aside class="lg:col-span-4 space-y-6">
            <div class="bg-surface-container-lowest rounded-xl border border-outline-variant p-6 shadow-sm">
                <h3 class="text-lg font-bold text-slate-900 mb-6">Account Overview</h3>
                <div class="space-y-4">
                    <div class="flex justify-between items-center p-3 bg-surface-container-low rounded-lg">
                        <span class="text-sm text-on-surface-variant font-medium">Total Orders</span>
                        <span class="font-bold text-slate-900">{{ Auth::user()->orders()->count() ?? 0 }}</span>
                    </div>
                    <div class="flex justify-between items-center p-3 bg-surface-container-low rounded-lg">
                        <span class="text-sm text-on-surface-variant font-medium">Upcoming Events</span>
                        <span class="font-bold text-primary">0</span>
                    </div>
                </div>
                <hr class="my-6 border-slate-100" />
                <div class="space-y-3">
                    <a href="{{ route('profile.edit') }}" class="flex items-center gap-3 text-sm text-slate-600 hover:text-primary transition-colors py-1">
                        <span class="material-symbols-outlined text-[20px]">person</span>
                        Edit Profile
                    </a>
                    <a href="#" class="flex items-center gap-3 text-sm text-slate-600 hover:text-primary transition-colors py-1">
                        <span class="material-symbols-outlined text-[20px]">help_outline</span>
                        Help Center
                    </a>
                </div>
            </div>

            <!-- Trust Badge (from design) -->
            <div class="bg-secondary-container/10 border border-secondary-container/30 rounded-xl p-6 flex items-start gap-4">
                <span class="material-symbols-outlined text-primary" style="font-variation-settings: 'FILL' 1;">verified_user</span>
                <div>
                    <h4 class="text-sm font-bold text-slate-900 mb-1">Buyer Guarantee</h4>
                    <p class="text-xs text-on-secondary-container leading-relaxed">Your admission is 100% guaranteed. We ensure your tickets are valid and delivered on time.</p>
                </div>
            </div>
        </aside>
    </div>
</main>
@endsection
