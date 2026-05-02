<div class="flex flex-col md:flex-row gap-8">
    <!-- Sidebar Filters -->
    <aside class="w-full md:w-[280px] shrink-0 space-y-6">
        <div class="bg-white dark:bg-zinc-900 p-6 rounded-[2rem] border border-slate-100 dark:border-zinc-800 shadow-xl shadow-slate-100/50 dark:shadow-none sticky top-24">
            <div class="flex items-center justify-between mb-8">
                <h3 class="text-xl font-black text-slate-900 dark:text-white tracking-tight">Filters</h3>
                <button wire:click="resetFilters" class="text-indigo-600 dark:text-indigo-400 text-[10px] font-black uppercase tracking-widest hover:underline">Reset All</button>
            </div>

            <!-- Realtime Search Box -->
            <div class="mb-8">
                <label class="block text-[10px] font-black text-slate-400 dark:text-zinc-500 uppercase tracking-widest mb-3 ml-1">Search Events</label>
                <div class="relative group">
                    <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-indigo-600 transition-colors">search</span>
                    <input type="text" wire:model.live.debounce.300ms="search" 
                           placeholder="What are you looking for?"
                           class="w-full pl-12 pr-4 py-4 rounded-2xl bg-slate-50 dark:bg-zinc-800 border-none focus:ring-4 focus:ring-indigo-500/20 dark:text-white text-sm font-bold placeholder:font-medium placeholder:text-slate-400 transition-all shadow-inner">
                    <div wire:loading wire:target="search" class="absolute right-4 top-1/2 -translate-y-1/2">
                        <div class="w-4 h-4 border-2 border-indigo-600 border-t-transparent rounded-full animate-spin"></div>
                    </div>
                </div>
            </div>

            <!-- Category Filter -->
            <div class="space-y-4 mb-8">
                <p class="text-[10px] font-black text-slate-400 dark:text-zinc-500 uppercase tracking-widest ml-1">Category</p>
                <div class="space-y-3">
                    @foreach(['music' => 'Music & Concerts', 'sports' => 'Sports & Games', 'theater' => 'Arts & Theatre', 'festival' => 'Festivals', 'other' => 'Other'] as $value => $label)
                    <label class="flex items-center gap-3 cursor-pointer group">
                        <input type="checkbox" wire:model.live="categories" value="{{ $value }}"
                            class="rounded-lg border-slate-200 dark:border-zinc-700 text-indigo-600 focus:ring-indigo-500 h-5 w-5 transition-all shadow-sm" />
                        <span class="text-sm font-bold text-slate-600 dark:text-zinc-400 group-hover:text-slate-900 dark:group-hover:text-white transition-colors">{{ $label }}</span>
                    </label>
                    @endforeach
                </div>
            </div>

            <!-- Date Filter -->
            <div class="space-y-4 mb-8">
                <p class="text-[10px] font-black text-slate-400 dark:text-zinc-500 uppercase tracking-widest ml-1">When</p>
                <select wire:model.live="dateRange"
                        class="w-full rounded-2xl border-none text-sm font-bold bg-slate-50 dark:bg-zinc-800 text-slate-900 dark:text-white focus:ring-4 focus:ring-indigo-500/20 transition-all p-4 shadow-inner">
                    <option value="">Anytime</option>
                    <option value="today">Today</option>
                    <option value="weekend">This Weekend</option>
                    <option value="30days">Next 30 Days</option>
                </select>
            </div>

            <!-- Price Range -->
            <div class="space-y-4 mb-8">
                <div class="flex justify-between items-center ml-1">
                    <p class="text-[10px] font-black text-slate-400 dark:text-zinc-500 uppercase tracking-widest">Max Price</p>
                    <span class="text-xs font-black text-indigo-600">€{{ $maxPrice }}</span>
                </div>
                <input type="range" wire:model.live="maxPrice" min="0" max="500"
                       class="w-full h-2 bg-slate-100 dark:bg-zinc-800 rounded-lg appearance-none cursor-pointer accent-indigo-600" />
            </div>

            <!-- Location -->
            <div class="space-y-4">
                <p class="text-[10px] font-black text-slate-400 dark:text-zinc-500 uppercase tracking-widest ml-1">Location</p>
                <div class="relative group">
                    <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-indigo-600 transition-colors">location_on</span>
                    <input type="text" wire:model.live.debounce.300ms="city"
                           placeholder="Search City..."
                           class="w-full pl-12 pr-4 py-4 rounded-2xl bg-slate-50 dark:bg-zinc-800 border-none focus:ring-4 focus:ring-indigo-500/20 dark:text-white text-sm font-bold placeholder:font-medium placeholder:text-slate-400 transition-all shadow-inner" />
                </div>
            </div>
        </div>
    </aside>

    <!-- Main Content -->
    <section class="flex-1">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-6 mb-12">
            <div>
                <h1 class="text-4xl font-black text-slate-900 dark:text-white tracking-tighter">Browse Events</h1>
                <p class="text-slate-500 dark:text-zinc-500 font-medium mt-2 flex items-center gap-2">
                    <span class="inline-block w-2 h-2 bg-indigo-500 rounded-full animate-pulse"></span>
                    {{ $events->total() }} results found
                </p>
            </div>
            <div class="flex items-center gap-4">
                <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Sort by</span>
                <select wire:model.live="sort"
                        class="rounded-2xl border-none text-xs font-black bg-white dark:bg-zinc-900 text-slate-700 dark:text-zinc-300 focus:ring-4 focus:ring-indigo-500/20 shadow-xl shadow-slate-100/50 dark:shadow-none p-4 pr-10">
                    <option value="date">Date: Soonest</option>
                    <option value="price_asc">Price: Low to High</option>
                    <option value="newest">Newly Listed</option>
                </select>
            </div>
        </div>

        <!-- Event Grid -->
        <div wire:loading.class="opacity-50 blur-sm scale-95 transition-all duration-300" wire:target="search, categories, dateRange, type, maxPrice, city, sort">
            @if($events->count())
            <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-8">
                @foreach($events as $event)
                @php
                    $minPrice = $event->ticketTypes->min('price');
                    $categoryColors = [
                        'music'    => 'bg-indigo-50 text-indigo-700',
                        'sports'   => 'bg-slate-50 text-slate-700',
                        'theater'  => 'bg-purple-50 text-purple-700',
                        'festival' => 'bg-orange-50 text-orange-700',
                    ];
                    $colorClass = $categoryColors[$event->category] ?? 'bg-slate-50 text-slate-700';
                @endphp
                <a href="{{ route('public.events.show', $event->slug) }}"
                   class="bg-white dark:bg-zinc-900 rounded-[2.5rem] border border-slate-50 dark:border-zinc-800 overflow-hidden shadow-2xl shadow-slate-100/50 dark:shadow-none hover:shadow-indigo-100 dark:hover:shadow-none hover:-translate-y-2 transition-all duration-500 flex flex-col group relative">
                    
                    <div class="h-52 overflow-hidden relative">
                        @if($event->image)
                            <img class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110"
                                 src="{{ Storage::url($event->image) }}" alt="{{ $event->title }}" />
                        @else
                            <div class="w-full h-full bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center">
                                <span class="material-symbols-outlined text-white text-5xl">event</span>
                            </div>
                        @endif
                        <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent"></div>
                        <div class="absolute top-4 left-4">
                            <span class="bg-white/90 backdrop-blur-md px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-tighter text-slate-900 shadow-lg">{{ $event->category }}</span>
                        </div>
                    </div>

                    <div class="p-6 flex flex-col flex-1">
                        <h4 class="text-xl font-black text-slate-900 dark:text-white mb-4 line-clamp-1 group-hover:text-indigo-600 transition-colors">{{ $event->title }}</h4>
                        
                        <div class="space-y-2 mb-6">
                            <div class="flex items-center gap-3 text-slate-500 dark:text-zinc-500 text-[11px] font-bold">
                                <span class="material-symbols-outlined text-sm">calendar_month</span>
                                {{ $event->start_date->format('M d, Y') }}
                            </div>
                            <div class="flex items-center gap-3 text-slate-500 dark:text-zinc-500 text-[11px] font-bold">
                                <span class="material-symbols-outlined text-sm">location_on</span>
                                {{ $event->city }}
                            </div>
                        </div>

                        <div class="mt-auto flex items-center justify-between pt-6 border-t border-slate-50 dark:border-zinc-800">
                            <div>
                                <p class="text-[10px] text-slate-400 uppercase font-black tracking-widest">From</p>
                                <p class="text-xl font-black text-indigo-600">
                                    {{ $minPrice > 0 ? '€'.number_format($minPrice, 2) : 'Free' }}
                                </p>
                            </div>
                            <span class="bg-indigo-600 text-white px-5 py-2.5 rounded-xl text-[11px] font-black uppercase tracking-widest shadow-lg shadow-indigo-100 dark:shadow-none group-hover:scale-105 transition-all">
                                Tickets
                            </span>
                        </div>
                    </div>
                </a>
                @endforeach
            </div>

            <div class="mt-16">
                {{ $events->links() }}
            </div>

            @else
            <div class="text-center py-32 bg-white dark:bg-zinc-900 rounded-[3rem] border border-slate-50 dark:border-zinc-800">
                <span class="material-symbols-outlined text-7xl text-slate-200 dark:text-zinc-800 block mb-6">search_off</span>
                <h2 class="text-2xl font-black text-slate-900 dark:text-white mb-2">No matching events</h2>
                <p class="text-slate-500 font-medium mb-8">We couldn't find anything matching your current filters.</p>
                <button wire:click="resetFilters" class="bg-indigo-600 text-white px-8 py-4 rounded-2xl font-bold shadow-lg shadow-indigo-100 dark:shadow-none hover:bg-indigo-700 transition-all active:scale-95">Clear All Filters</button>
            </div>
            @endif
        </div>
    </section>
</div>
