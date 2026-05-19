@extends('layouts.public')

@section('content')
<!-- Hero Image -->
<div class="relative h-96 overflow-hidden">
    @if($event->image)
        <img class="w-full h-full object-cover" src="{{ $event->image_url }}" alt="{{ $event->title }}" />
    @else
        <div class="w-full h-full bg-gradient-to-br from-indigo-600 to-purple-700"></div>
    @endif
    <div class="absolute inset-0 bg-slate-900/50 dark:bg-slate-950/60 backdrop-blur-[1px]"></div>
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
            <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-800 p-8">
                <h2 class="text-2xl font-semibold mb-6 dark:text-white">About this Event</h2>
                <p class="text-[#464555] dark:text-slate-400 leading-relaxed">{{ $event->description }}</p>

                <div class="mt-8 grid grid-cols-2 md:grid-cols-4 gap-6 pt-6 border-t border-slate-100 dark:border-slate-800">
                    <div class="flex flex-col gap-1">
                        <span class="material-symbols-outlined text-[#4f46e5] dark:text-indigo-400">calendar_today</span>
                        <span class="text-xs text-[#777587] dark:text-slate-500 font-medium uppercase">Date</span>
                        <span class="text-sm font-semibold dark:text-white">{{ $event->start_date->format('d M Y') }}</span>
                    </div>
                    <div class="flex flex-col gap-1">
                        <span class="material-symbols-outlined text-[#4f46e5] dark:text-indigo-400">schedule</span>
                        <span class="text-xs text-[#777587] dark:text-slate-500 font-medium uppercase">Time</span>
                        <span class="text-sm font-semibold dark:text-white">{{ $event->time ? \Carbon\Carbon::parse($event->time)->format('H:i') : 'TBA' }}</span>
                    </div>
                    <div class="flex flex-col gap-1">
                        <span class="material-symbols-outlined text-[#4f46e5] dark:text-indigo-400">location_on</span>
                        <span class="text-xs text-[#777587] dark:text-slate-500 font-medium uppercase">Venue</span>
                        <span class="text-sm font-semibold dark:text-white">{{ $event->venue?->name ?? 'TBA' }}</span>
                    </div>
                    <div class="flex flex-col gap-1">
                        <span class="material-symbols-outlined text-[#4f46e5] dark:text-indigo-400">confirmation_number</span>
                        <span class="text-xs text-[#777587] dark:text-slate-500 font-medium uppercase">Capacity</span>
                        <span class="text-sm font-semibold dark:text-white">{{ $event->capacity ? number_format($event->capacity) . ' seats' : 'Unlimited' }}</span>
                    </div>
                </div>
            </div>

            <!-- Organizer -->
            @if($event->organizer)
            <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-800 p-8">
                <h2 class="text-2xl font-semibold mb-6 dark:text-white">Organized by</h2>
                <div class="flex items-center gap-4">
                    @if($event->organizer->logo)
                        <img src="{{ Storage::url($event->organizer->logo) }}" alt="{{ $event->organizer->company_name }}" class="w-16 h-16 rounded-2xl object-cover" />
                    @else
                        <div class="w-16 h-16 rounded-2xl bg-[#e2dfff] dark:bg-indigo-900/30 flex items-center justify-center border dark:border-indigo-500/20">
                            <span class="material-symbols-outlined text-[#4f46e5] dark:text-indigo-400 text-3xl">business</span>
                        </div>
                    @endif
                    <div>
                        <h3 class="font-semibold text-lg dark:text-white">{{ $event->organizer->company_name }}</h3>
                        @if($event->organizer->website)
                            <a href="{{ $event->organizer->website }}" target="_blank" class="text-[#4f46e5] dark:text-indigo-400 text-sm hover:underline flex items-center gap-1">
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
            <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-800 p-6 sticky top-24">
                <h2 class="text-xl font-semibold mb-1 dark:text-white">Get your tickets</h2>
                <p class="text-xs text-slate-400 dark:text-slate-500 mb-6">Prices include all fees. Secure checkout via Stripe.</p>

                @if($event->ticketTypes->count())

                    @php
                        // Determine overall event state
                        $isPast       = $event->start_date->endOfDay()->isPast();
                        $anyAvailable = collect($ticketAvailability)->contains(fn($t) => $t['available'] > 0);
                        $allSoldOut   = collect($ticketAvailability)->every(fn($t) => $t['is_sold_out']);
                        $anyHeld      = collect($ticketAvailability)->contains(fn($t) => $t['is_held']);
                        $canBuy       = $anyAvailable && !$isPast;
                    @endphp

                    {{-- ── Global "Being purchased now" notice ── --}}
                    @if($anyHeld && !$anyAvailable && !$allSoldOut && !$isPast)
                    <div class="mb-5 flex items-start gap-3 bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-900/50 rounded-xl px-4 py-3">
                        <span class="material-symbols-outlined text-amber-500 text-xl mt-0.5" style="font-variation-settings:'FILL' 1">schedule</span>
                        <div>
                            <p class="text-sm font-bold text-amber-800 dark:text-amber-400">Being purchased right now</p>
                            <p class="text-xs text-amber-600 dark:text-amber-500 mt-0.5">Someone is completing their checkout. Tickets may become available shortly if they don't complete the purchase.</p>
                        </div>
                    </div>
                    @endif

                    <div class="space-y-3 mb-6">
                        @foreach($event->ticketTypes as $ticketType)
                        @php $avail = $ticketAvailability[$ticketType->id]; @endphp

                        <div @class([
                            'border rounded-xl p-4 transition-all',
                            'border-slate-200 dark:border-slate-700 hover:border-[#4f46e5]/50 dark:hover:border-indigo-500/50' => $avail['available'] > 0 && !$isPast,
                            'border-amber-200 dark:border-amber-900/50 bg-amber-50/50 dark:bg-amber-900/20' => $avail['is_held'] && !$avail['is_sold_out'] && !$isPast,
                            'border-slate-100 dark:border-slate-800 bg-slate-50 dark:bg-slate-800/50 opacity-60' => $avail['is_sold_out'] || $isPast,
                        ])>
                            {{-- Name + Price --}}
                            <div class="flex justify-between items-start mb-2">
                                <span class="font-semibold text-slate-900 dark:text-white">{{ $ticketType->name }}</span>
                                <span class="font-bold text-lg {{ $avail['is_sold_out'] || $isPast ? 'text-slate-400 dark:text-slate-500' : 'text-[#4f46e5] dark:text-indigo-400' }}">
                                    {{ $ticketType->price > 0 ? '€'.number_format($ticketType->price, 2) : 'Free' }}
                                </span>
                            </div>

                            @if($ticketType->description)
                                <p class="text-xs text-slate-500 dark:text-slate-400 mb-3">{{ $ticketType->description }}</p>
                            @endif

                            {{-- Status Badge --}}
                            <div class="flex items-center justify-between gap-2 flex-wrap">
                                @if($isPast)
                                    <span class="inline-flex items-center gap-1 text-xs font-bold text-slate-500 dark:text-slate-400 bg-slate-100 dark:bg-slate-800 px-2.5 py-1 rounded-full border dark:border-slate-700">
                                        <span class="material-symbols-outlined text-xs">history</span> Event Ended
                                    </span>
                                @elseif($avail['is_sold_out'])
                                    {{-- SOLD OUT --}}
                                    <span class="inline-flex items-center gap-1 text-xs font-bold text-slate-500 dark:text-slate-400 bg-slate-100 dark:bg-slate-800 px-2.5 py-1 rounded-full border dark:border-slate-700">
                                        <span class="material-symbols-outlined text-xs">block</span> Sold Out
                                    </span>

                                @elseif($avail['is_held'] && $avail['available'] === 0)
                                    {{-- TEMPORARILY HELD --}}
                                    <span class="inline-flex items-center gap-1.5 text-xs font-bold text-amber-700 dark:text-amber-400 bg-amber-100 dark:bg-amber-900/30 px-2.5 py-1 rounded-full animate-pulse border dark:border-amber-900/50">
                                        <span class="w-1.5 h-1.5 bg-amber-500 dark:bg-amber-400 rounded-full inline-block"></span>
                                        Temporarily Held
                                    </span>
                                    <span class="text-[11px] text-amber-600 dark:text-amber-500">Check back soon</span>

                                @elseif($avail['is_low'])
                                    {{-- LOW STOCK --}}
                                    <span class="inline-flex items-center gap-1 text-xs font-bold text-red-600 dark:text-red-400 bg-red-50 dark:bg-red-900/30 px-2.5 py-1 rounded-full border dark:border-red-900/50">
                                        <span class="material-symbols-outlined text-xs" style="font-variation-settings:'FILL' 1">local_fire_department</span>
                                        Only {{ $avail['available'] }} left!
                                    </span>
                                    @if($ticketType->max_per_order)
                                        <span class="text-[11px] text-slate-400 dark:text-slate-500">Max {{ $ticketType->max_per_order }} per order</span>
                                    @endif

                                @else
                                    {{-- AVAILABLE --}}
                                    <span class="inline-flex items-center gap-1 text-xs font-medium text-emerald-700 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-900/30 px-2.5 py-1 rounded-full border dark:border-emerald-900/50">
                                        <span class="w-1.5 h-1.5 bg-emerald-500 dark:bg-emerald-400 rounded-full inline-block"></span>
                                        {{ $avail['available'] }} available
                                    </span>
                                    @if($ticketType->max_per_order)
                                        <span class="text-[11px] text-slate-400 dark:text-slate-500">Max {{ $ticketType->max_per_order }} per order</span>
                                    @endif
                                @endif
                            </div>

                            {{-- Progress bar (stock fill) --}}
                            @if($avail['raw_stock'] > 0 && !$isPast)
                            @php
                                $pct = min(100, round(($avail['available'] / max(1, $avail['raw_stock'])) * 100));
                                $barColor = $avail['is_sold_out'] ? 'bg-slate-300 dark:bg-slate-600'
                                    : ($avail['is_held'] && $avail['available'] === 0 ? 'bg-amber-400 dark:bg-amber-500'
                                    : ($avail['is_low'] ? 'bg-red-400 dark:bg-red-500' : 'bg-emerald-400 dark:bg-emerald-500'));
                            @endphp
                            <div class="mt-3 h-1 bg-slate-100 dark:bg-slate-800 rounded-full overflow-hidden">
                                <div class="{{ $barColor }} h-full rounded-full transition-all duration-500" style="width: {{ $pct }}%"></div>
                            </div>
                            @endif
                        </div>
                        @endforeach
                    </div>

                    {{-- ── CTA Button ── --}}
                    @if($isPast)
                        <button disabled
                                class="block w-full bg-slate-100 dark:bg-slate-800 text-slate-400 dark:text-slate-500 text-center py-4 rounded-xl font-semibold cursor-not-allowed">
                            <span class="flex items-center justify-center gap-2">
                                <span class="material-symbols-outlined text-sm">history</span>
                                Event Ended
                            </span>
                        </button>
                    @elseif($canBuy)
                        <a href="{{ route('public.checkout', $event->slug) }}"
                           wire:navigate
                           class="block w-full bg-[#4f46e5] dark:bg-indigo-600 text-white text-center py-4 rounded-xl font-semibold hover:bg-[#4f46e5]/90 dark:hover:bg-indigo-500 transition-all active:scale-95 shadow-lg shadow-indigo-100 dark:shadow-none">
                            Buy Now
                        </a>
                    @elseif($anyHeld)
                        <button disabled
                                class="block w-full bg-amber-400/60 dark:bg-amber-500/20 text-amber-900 dark:text-amber-200 border dark:border-amber-500/30 text-center py-4 rounded-xl font-semibold cursor-not-allowed">
                            <span class="flex items-center justify-center gap-2">
                                <span class="material-symbols-outlined text-sm">hourglass_top</span>
                                In Checkout — Check Back Soon
                            </span>
                        </button>
                    @elseif($allSoldOut)
                        <button disabled
                                class="block w-full bg-slate-100 dark:bg-slate-800 text-slate-400 dark:text-slate-500 text-center py-4 rounded-xl font-semibold cursor-not-allowed">
                            Sold Out
                        </button>
                    @endif

                @else
                    <div class="text-center py-8 text-slate-400 dark:text-slate-500">
                        <span class="material-symbols-outlined text-4xl block mb-3">confirmation_number</span>
                        <p class="text-sm">Tickets coming soon</p>
                    </div>
                @endif

                <p class="text-[10px] text-center text-slate-400 dark:text-slate-500 mt-4">🔒 100% Buyer Guarantee. Secure checkout powered by Stripe.</p>
            </div>
        </div>

    </div>
</div>
@endsection
