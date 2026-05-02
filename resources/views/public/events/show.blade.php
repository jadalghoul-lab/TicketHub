@extends('layouts.public')

@section('content')
<!-- Hero Image -->
<div class="relative h-96 overflow-hidden">
    @if($event->image)
        <img class="w-full h-full object-cover" src="{{ Storage::url($event->image) }}" alt="{{ $event->title }}" />
    @else
        <div class="w-full h-full bg-gradient-to-br from-indigo-600 to-purple-700"></div>
    @endif
    <div class="absolute inset-0 bg-slate-900/50"></div>
    <div class="absolute bottom-0 left-0 right-0 p-10 max-w-screen-2xl mx-auto">
        <span class="bg-[#4f46e5] text-white text-xs font-semibold uppercase px-3 py-1 rounded-full">{{ $event->category }}</span>
        <h1 class="text-white text-4xl md:text-5xl font-bold mt-4 mb-2 drop-shadow-lg">{{ $event->title }}</h1>
        <p class="text-white/80 flex items-center gap-2">
            <span class="material-symbols-outlined text-sm">location_on</span>
            {{ $event->venue?->name ?? $event->city }}, {{ $event->country }}
        </p>
    </div>
</div>

<div class="max-w-screen-2xl mx-auto px-6 py-12">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-8">
            <!-- Event Details -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-8">
                <h2 class="text-2xl font-semibold mb-6">About this Event</h2>
                <p class="text-[#464555] leading-relaxed">{{ $event->description }}</p>

                <div class="mt-8 grid grid-cols-2 md:grid-cols-4 gap-6 pt-6 border-t border-slate-100">
                    <div class="flex flex-col gap-1">
                        <span class="material-symbols-outlined text-[#4f46e5]">calendar_today</span>
                        <span class="text-xs text-[#777587] font-medium uppercase">Date</span>
                        <span class="text-sm font-semibold">{{ $event->start_date->format('d M Y') }}</span>
                    </div>
                    <div class="flex flex-col gap-1">
                        <span class="material-symbols-outlined text-[#4f46e5]">schedule</span>
                        <span class="text-xs text-[#777587] font-medium uppercase">Time</span>
                        <span class="text-sm font-semibold">{{ $event->time ? \Carbon\Carbon::parse($event->time)->format('H:i') : 'TBA' }}</span>
                    </div>
                    <div class="flex flex-col gap-1">
                        <span class="material-symbols-outlined text-[#4f46e5]">location_on</span>
                        <span class="text-xs text-[#777587] font-medium uppercase">Venue</span>
                        <span class="text-sm font-semibold">{{ $event->venue?->name ?? 'TBA' }}</span>
                    </div>
                    <div class="flex flex-col gap-1">
                        <span class="material-symbols-outlined text-[#4f46e5]">confirmation_number</span>
                        <span class="text-xs text-[#777587] font-medium uppercase">Capacity</span>
                        <span class="text-sm font-semibold">{{ $event->capacity ? number_format($event->capacity) . ' seats' : 'Unlimited' }}</span>
                    </div>
                </div>
            </div>

            <!-- Organizer -->
            @if($event->organizer)
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-8">
                <h2 class="text-2xl font-semibold mb-6">Organized by</h2>
                <div class="flex items-center gap-4">
                    @if($event->organizer->logo)
                        <img src="{{ Storage::url($event->organizer->logo) }}" alt="{{ $event->organizer->company_name }}" class="w-16 h-16 rounded-2xl object-cover" />
                    @else
                        <div class="w-16 h-16 rounded-2xl bg-[#e2dfff] flex items-center justify-center">
                            <span class="material-symbols-outlined text-[#4f46e5] text-3xl">business</span>
                        </div>
                    @endif
                    <div>
                        <h3 class="font-semibold text-lg">{{ $event->organizer->company_name }}</h3>
                        @if($event->organizer->website)
                            <a href="{{ $event->organizer->website }}" target="_blank" class="text-[#4f46e5] text-sm hover:underline flex items-center gap-1">
                                <span class="material-symbols-outlined text-xs">open_in_new</span> Visit website
                            </a>
                        @endif
                    </div>
                </div>
            </div>
            @endif
        </div>

        <!-- Ticket Sidebar -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 sticky top-24">
                <h2 class="text-xl font-semibold mb-6">Get your tickets</h2>

                @if($event->ticketTypes->count())
                    <div class="space-y-4 mb-6">
                        @foreach($event->ticketTypes as $ticketType)
                        <div class="border border-slate-200 rounded-xl p-4 hover:border-[#4f46e5] transition-colors">
                            <div class="flex justify-between items-start mb-1">
                                <span class="font-semibold">{{ $ticketType->name }}</span>
                                <span class="text-[#4f46e5] font-bold text-lg">
                                    {{ $ticketType->price > 0 ? '€'.number_format($ticketType->price, 2) : 'Free' }}
                                </span>
                            </div>
                            @if($ticketType->description)
                                <p class="text-xs text-[#464555] mb-3">{{ $ticketType->description }}</p>
                            @endif
                            <div class="flex items-center justify-between text-xs text-[#777587]">
                                <span>{{ $ticketType->quantity }} available</span>
                                @if($ticketType->max_per_order)
                                    <span>Max {{ $ticketType->max_per_order }} per order</span>
                                @endif
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <a href="{{ route('public.checkout', $event->slug) }}" wire:navigate class="block w-full bg-[#4f46e5] text-white text-center py-4 rounded-xl font-semibold hover:bg-[#4f46e5]/90 transition-all active:scale-95">
                        Buy Now
                    </a>
                @else
                    <div class="text-center py-8 text-[#464555]">
                        <span class="material-symbols-outlined text-4xl text-[#c7c4d8] block mb-3">confirmation_number</span>
                        <p class="text-sm">Tickets coming soon</p>
                    </div>
                @endif

                <p class="text-[10px] text-center text-[#777587] mt-4">100% Buyer Guarantee. Secure checkout powered by Stripe.</p>
            </div>
        </div>
    </div>
</div>
@endsection
