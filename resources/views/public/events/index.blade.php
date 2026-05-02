@extends('layouts.public')

@section('content')
<main class="max-w-screen-2xl mx-auto px-6 py-8">
    <div class="flex flex-col md:flex-row gap-8">

        <!-- Sidebar Filters -->
        <aside class="w-full md:w-[280px] shrink-0 space-y-6">
            <form method="GET" action="{{ route('public.events.index') }}" id="filter-form">
                <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-2xl font-semibold text-slate-900">Filters</h3>
                        <a href="{{ route('public.events.index') }}" class="text-[#3525cd] text-xs font-semibold">Reset All</a>
                    </div>

                    <!-- Category Filter -->
                    <div class="space-y-4 mb-8">
                        <p class="text-sm font-medium text-slate-900">Category</p>
                        <div class="space-y-3">
                            @foreach(['music' => 'Music & Concerts', 'sports' => 'Sports & Games', 'theater' => 'Arts & Theatre', 'festival' => 'Festivals', 'other' => 'Other'] as $value => $label)
                            <label class="flex items-center gap-3 cursor-pointer group">
                                <input type="checkbox" name="category[]" value="{{ $value }}"
                                    {{ in_array($value, (array) request('category')) ? 'checked' : '' }}
                                    onchange="document.getElementById('filter-form').submit()"
                                    class="rounded border-slate-300 text-[#3525cd] focus:ring-[#3525cd] h-4 w-4" />
                                <span class="text-sm text-slate-600 group-hover:text-slate-900 transition-colors">{{ $label }}</span>
                            </label>
                            @endforeach
                        </div>
                    </div>

                    <!-- Date Filter -->
                    <div class="space-y-4 mb-8">
                        <p class="text-sm font-medium text-slate-900">When</p>
                        <select name="date_range" onchange="document.getElementById('filter-form').submit()"
                                class="w-full rounded-lg border-slate-200 text-sm bg-slate-50 focus:border-[#3525cd] focus:ring-[#3525cd]">
                            <option value="">Anytime</option>
                            <option value="today" {{ request('date_range') == 'today' ? 'selected' : '' }}>Today</option>
                            <option value="weekend" {{ request('date_range') == 'weekend' ? 'selected' : '' }}>This Weekend</option>
                            <option value="30days" {{ request('date_range') == '30days' ? 'selected' : '' }}>Next 30 Days</option>
                        </select>
                    </div>

                    <!-- Type Filter (Free/Paid) -->
                    <div class="space-y-4 mb-8">
                        <p class="text-sm font-medium text-slate-900">Event Type</p>
                        <select name="type" onchange="document.getElementById('filter-form').submit()"
                                class="w-full rounded-lg border-slate-200 text-sm bg-slate-50 focus:border-[#3525cd] focus:ring-[#3525cd]">
                            <option value="">All Types</option>
                            <option value="paid" {{ request('type') == 'paid' ? 'selected' : '' }}>Paid Events</option>
                            <option value="free" {{ request('type') == 'free' ? 'selected' : '' }}>Free Entry</option>
                        </select>
                    </div>

                    <!-- Availability -->
                    <div class="space-y-4 mb-8">
                        <label class="flex items-center gap-3 cursor-pointer group">
                            <input type="checkbox" name="available_only" value="1"
                                   {{ request('available_only') ? 'checked' : '' }}
                                   onchange="document.getElementById('filter-form').submit()"
                                   class="rounded border-slate-300 text-[#3525cd] focus:ring-[#3525cd] h-4 w-4" />
                            <span class="text-sm font-medium text-slate-900">Available Only</span>
                        </label>
                    </div>

                    <!-- Price Range -->
                    <div class="space-y-4 mb-8">
                        <div class="flex justify-between items-center">
                            <p class="text-sm font-medium text-slate-900">Price Range</p>
                            <span class="text-xs text-slate-500">€0 – €{{ request('max_price', 500) }}+</span>
                        </div>
                        <input type="range" name="max_price" min="0" max="500" value="{{ request('max_price', 500) }}"
                               onchange="document.getElementById('filter-form').submit()"
                               class="w-full h-1.5 bg-slate-200 rounded-lg appearance-none cursor-pointer accent-[#3525cd]" />
                    </div>

                    <!-- Location -->
                    <div class="space-y-4">
                        <p class="text-sm font-medium text-slate-900">Location</p>
                        <div class="relative">
                            <span class="material-symbols-outlined absolute left-3 top-2.5 text-slate-400 text-[18px]">location_on</span>
                            <input type="text" name="city" value="{{ request('city') }}"
                                   placeholder="City or Venue"
                                   class="w-full pl-10 pr-4 py-2 rounded-lg border-slate-200 text-sm bg-slate-50 focus:border-[#3525cd] focus:ring-[#3525cd]" />
                        </div>
                        <button type="submit" class="w-full bg-[#3525cd] text-white py-2 rounded-lg text-sm font-medium hover:bg-[#3525cd]/90 transition-all active:scale-95">
                            Apply Filters
                        </button>
                    </div>
                </div>
            </form>

            <!-- Promo Card -->
            <div class="relative h-64 rounded-xl overflow-hidden group">
                <img src="https://lh3.googleusercontent.com/aida-public/AB6AXuC1VxYULTsDTI9iFolVRJFOJq9nEGVM4QWLxrt0qb505PjNdg5kWBGYK_3vm-d7TV_WCcvyXgipwbe6iicYK65NtL0VnhIQtFI-5eokbPip3T3RTswd-km4vnYQg3UbHJ4hKMp1-yQl5-6Wa4vQ6MF4Nj-d2KKOyZ7MecRjknCjlF1TSGwqKYrMLX6pYNvhhFixNHZ5xf6S3HiGseEl0gw61f8bZZWCtMQXn-7j-xj8mTVoYnpms4gTJOWnpHEkWzvS6XdK9gydr3d5"
                     alt="Promo" class="absolute inset-0 w-full h-full object-cover transition-transform duration-500 group-hover:scale-110" />
                <div class="absolute inset-0 bg-gradient-to-t from-slate-900 via-slate-900/40 to-transparent"></div>
                <div class="absolute bottom-0 left-0 p-6">
                    <p class="text-white font-bold text-lg mb-2">Summer Festival Pass</p>
                    <a href="{{ route('public.events.index', ['category' => 'festival']) }}"
                       class="text-white text-sm font-semibold flex items-center gap-1 group/btn">
                        Learn more <span class="material-symbols-outlined text-[16px] transition-transform group-hover/btn:translate-x-1">arrow_forward</span>
                    </a>
                </div>
            </div>
        </aside>

        <!-- Main Content -->
        <section class="flex-1">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
                <div>
                    <h1 class="text-4xl font-bold text-slate-900">Browse Events</h1>
                    <p class="text-slate-500 mt-1">
                        {{ $events->total() }} event{{ $events->total() != 1 ? 's' : '' }} found
                        @if(request('city')) in <strong>{{ request('city') }}</strong>@endif
                        @if(request('search')) matching "<strong>{{ request('search') }}</strong>"@endif
                    </p>
                </div>
                <div class="flex items-center gap-3">
                    <span class="text-xs text-slate-500">Sort by:</span>
                    <form method="GET" action="{{ route('public.events.index') }}" id="sort-form">
                        @foreach(request()->except('sort') as $key => $val)
                            @if(is_array($val))
                                @foreach($val as $v)
                                    <input type="hidden" name="{{ $key }}[]" value="{{ $v }}" />
                                @endforeach
                            @else
                                <input type="hidden" name="{{ $key }}" value="{{ $val }}" />
                            @endif
                        @endforeach
                        <select name="sort" onchange="document.getElementById('sort-form').submit()"
                                class="rounded-lg border-slate-200 text-sm bg-white focus:border-[#3525cd] focus:ring-[#3525cd]">
                            <option value="date" {{ request('sort') == 'date' ? 'selected' : '' }}>Date: Soonest</option>
                            <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Price: Low to High</option>
                            <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Newly Listed</option>
                        </select>
                    </form>
                </div>
            </div>

            <!-- Event Grid -->
            @if($events->count())
            <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-6">
                @foreach($events as $event)
                @php
                    $minPrice = $event->ticketTypes->min('price');
                    $totalTickets = $event->ticketTypes->sum('quantity');
                    $categoryColors = [
                        'music'    => 'bg-indigo-50 text-indigo-700',
                        'sports'   => 'bg-slate-100 text-slate-700',
                        'theater'  => 'bg-purple-50 text-purple-700',
                        'festival' => 'bg-orange-50 text-orange-700',
                        'other'    => 'bg-slate-100 text-slate-700',
                    ];
                    $colorClass = $categoryColors[$event->category] ?? 'bg-slate-100 text-slate-700';
                @endphp
                <a href="{{ route('public.events.show', $event->slug) }}"
                   class="bg-white rounded-xl border border-slate-200 overflow-hidden shadow-sm hover:shadow-md transition-shadow flex flex-col group">
                    <!-- Image -->
                    <div class="relative h-48 overflow-hidden">
                        @if($event->image)
                            <img class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                                 src="{{ Storage::url($event->image) }}" alt="{{ $event->title }}" />
                        @else
                            <div class="w-full h-full bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center">
                                <span class="material-symbols-outlined text-white text-5xl">event</span>
                            </div>
                        @endif

                        <!-- Badge top right -->
                        @if($event->start_date->diffInDays(now()) <= 3 && $event->start_date->isFuture())
                            <div class="absolute top-3 right-3">
                                <span class="bg-red-50 text-red-600 text-xs font-semibold px-2 py-1 rounded shadow-sm">Selling Fast</span>
                            </div>
                        @elseif($event->ticketTypes->isNotEmpty() && $minPrice == 0)
                            <div class="absolute top-3 right-3">
                                <span class="bg-green-50 text-green-700 text-xs font-semibold px-2 py-1 rounded shadow-sm">Free</span>
                            </div>
                        @endif

                        <!-- Tickets left badge -->
                        @if($totalTickets > 0 && $totalTickets <= 50)
                            <div class="absolute bottom-3 left-3">
                                <span class="bg-slate-900/80 backdrop-blur-sm text-white text-xs font-semibold px-2.5 py-1 rounded-full flex items-center gap-1">
                                    <span class="material-symbols-outlined text-[14px]" style="font-variation-settings: 'FILL' 1;">confirmation_number</span>
                                    {{ $totalTickets }} left
                                </span>
                            </div>
                        @endif
                    </div>

                    <!-- Card Body -->
                    <div class="p-5 flex flex-col flex-1">
                        <div class="flex items-center gap-2 mb-3">
                            <span class="text-[10px] font-bold uppercase tracking-wider px-2 py-0.5 rounded {{ $colorClass }}">
                                {{ ucfirst($event->category) }}
                            </span>
                            @if($totalTickets > 50)
                                <span class="text-slate-400 text-xs">• {{ number_format($totalTickets) }} Tickets Left</span>
                            @elseif($totalTickets == 0)
                                <span class="text-red-400 text-xs">• Sold Out</span>
                            @endif
                        </div>

                        <h4 class="text-xl font-semibold text-slate-900 mb-1 line-clamp-1">{{ $event->title }}</h4>

                        <div class="flex items-center gap-2 text-slate-500 text-sm mb-1">
                            <span class="material-symbols-outlined text-[18px]">calendar_today</span>
                            {{ $event->start_date->format('M d, Y') }}
                            @if($event->time)• {{ \Carbon\Carbon::parse($event->time)->format('g:i A') }}@endif
                        </div>

                        <div class="flex items-center gap-2 text-slate-500 text-sm mb-4">
                            <span class="material-symbols-outlined text-[18px]">location_on</span>
                            {{ $event->venue?->name ?? ($event->city ? $event->city . ', ' . $event->country : 'TBA') }}
                        </div>

                        <div class="mt-auto flex items-center justify-between pt-4 border-t border-slate-100">
                            <div>
                                <p class="text-xs text-slate-400 uppercase font-semibold">From</p>
                                <p class="text-2xl font-semibold text-[#3525cd]">
                                    {{ $minPrice > 0 ? '€'.number_format($minPrice, 2) : 'Free' }}
                                </p>
                            </div>
                            <span class="bg-[#3525cd] text-white px-4 py-2 rounded-lg text-sm font-medium active:scale-95 transition-transform">
                                View Details
                            </span>
                        </div>
                    </div>
                </a>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-12 flex justify-center items-center gap-2">
                {{ $events->withQueryString()->links('vendor.pagination.simple-custom') }}
            </div>

            @else
            <!-- Empty State -->
            <div class="text-center py-24 text-slate-500">
                <span class="material-symbols-outlined text-6xl text-slate-300 block mb-4">event_busy</span>
                <h2 class="text-2xl font-semibold mb-2 text-slate-700">No events found</h2>
                <p class="text-sm mb-6">Try adjusting your filters or search for something else.</p>
                <a href="{{ route('public.events.index') }}"
                   class="inline-block bg-[#3525cd] text-white px-6 py-3 rounded-xl font-medium hover:bg-[#3525cd]/90 transition-all active:scale-95">
                    Clear All Filters
                </a>
            </div>
            @endif
        </section>
    </div>
</main>
@endsection
