<div class="relative w-full max-w-3xl mx-auto" x-data="{ open: false }" @click.away="open = false">
    <form wire:submit.prevent="performSearch" 
          class="bg-white/95 dark:bg-slate-900/95 backdrop-blur shadow-2xl p-2 rounded-2xl flex flex-col md:flex-row gap-2 border border-slate-100 dark:border-slate-800 transition-all duration-500 focus-within:ring-4 focus-within:ring-indigo-500/30 focus-within:border-indigo-500/50 focus-within:scale-[1.01] group outline-none">
        
        <div class="flex-grow flex items-center px-4 py-3 gap-3">
            <span class="material-symbols-outlined text-slate-400 group-focus-within:text-indigo-600 dark:group-focus-within:text-indigo-400 transition-colors">search</span>
            <input wire:model.live.debounce.300ms="search"
                   @focus="open = true"
                   @input="open = true"
                   class="w-full bg-transparent border-none focus:ring-0 focus:outline-none text-slate-900 dark:text-white font-bold placeholder:text-slate-400 placeholder:font-medium"
                   placeholder="Find your next event..." type="text" autocomplete="off"/>
        </div>
        
        <div class="w-px bg-slate-200 dark:bg-slate-800 hidden md:block my-2"></div>
        
        <div class="flex items-center px-4 py-3 gap-3">
            <span class="material-symbols-outlined text-slate-400 group-focus-within:text-indigo-600 dark:group-focus-within:text-indigo-400 transition-colors">location_on</span>
            <input wire:model.live="city"
                   @focus="open = true"
                   @input="open = true"
                   class="w-32 bg-transparent border-none focus:ring-0 focus:outline-none text-slate-900 dark:text-white text-sm font-bold placeholder:text-slate-400"
                   placeholder="City..." type="text" autocomplete="off"/>
        </div>
        
        <button type="submit"
                wire:loading.attr="disabled"
                class="bg-indigo-600 dark:bg-indigo-500 text-white px-8 py-4 rounded-xl flex items-center justify-center gap-3 hover:bg-indigo-700 dark:hover:bg-indigo-600 transition-all active:scale-95 font-black shadow-lg shadow-indigo-200 dark:shadow-none disabled:opacity-70 overflow-hidden relative group/btn">
            <span wire:loading.remove wire:target="performSearch" class="flex items-center gap-2">
                Search
                <span class="material-symbols-outlined text-sm group-hover/btn:translate-x-1 transition-transform">arrow_forward</span>
            </span>
            <span wire:loading wire:target="performSearch" class="flex items-center gap-2">
                <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                Searching...
            </span>
        </button>
    </form>

    <!-- Autocomplete Dropdown -->
    <div x-show="open && ($wire.search.length >= 2 || $wire.city.length >= 2)" 
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 translate-y-4"
         x-transition:enter-end="opacity-100 translate-y-0"
         class="absolute top-full left-0 right-0 mt-4 bg-white dark:bg-slate-900 rounded-[2rem] shadow-2xl border border-slate-100 dark:border-slate-800 overflow-hidden z-50 p-2"
         x-cloak>
        
        @if(count($results) > 0)
            <div class="p-4 border-b border-slate-50 dark:border-slate-800">
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Suggested Events</p>
            </div>

            <div class="max-h-96 overflow-y-auto">
                @foreach($results as $event)
                    <a href="{{ route('public.events.show', $event->slug) }}" 
                       wire:navigate
                       class="flex items-center gap-4 p-4 hover:bg-slate-50 dark:hover:bg-slate-800 transition-all group rounded-2xl">
                        <div class="w-16 h-12 bg-slate-100 dark:bg-slate-800 rounded-lg overflow-hidden flex-shrink-0">
                            @if($event->image)
                                <img src="{{ $event->image_url }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-slate-300 dark:text-slate-700">
                                    <span class="material-symbols-outlined text-sm">image</span>
                                </div>
                            @endif
                        </div>
                        <div class="flex-grow min-w-0">
                            <h4 class="font-bold text-slate-900 dark:text-white truncate group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors">{{ $event->title }}</h4>
                            <div class="flex items-center gap-3 text-[10px] font-bold text-slate-400">
                                <span class="flex items-center gap-1">
                                    <span class="material-symbols-outlined text-[12px]">calendar_month</span>
                                    {{ $event->start_date->format('M d, Y') }}
                                </span>
                                <span class="flex items-center gap-1">
                                    <span class="material-symbols-outlined text-[12px]">location_on</span>
                                    {{ $event->city }}
                                </span>
                            </div>
                        </div>
                        <div class="text-right flex-shrink-0">
                            @php $minPrice = $event->ticketTypes->min('price'); @endphp
                            <p class="text-sm font-black text-indigo-600 dark:text-indigo-400">
                                {{ $minPrice > 0 ? '€' . number_format($minPrice, 2) : 'Free' }}
                            </p>
                            <span class="text-[10px] text-slate-400 font-bold uppercase">Book Now</span>
                        </div>
                    </a>
                @endforeach
            </div>

            <a href="{{ route('public.events.index', ['search' => $search, 'city' => $city]) }}" 
               wire:navigate
               class="block p-4 text-center bg-slate-50 dark:bg-slate-800 text-xs font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest hover:text-indigo-600 dark:hover:text-indigo-400 transition-all rounded-b-[2rem]">
                See all results for "{{ $search }}"
            </a>
        @else
            <div class="p-8 text-center">
                <div wire:loading.remove wire:target="search">
                    <span class="material-symbols-outlined text-4xl text-slate-200 dark:text-slate-700 mb-4">search_off</span>
                    <p class="text-slate-500 dark:text-slate-400 font-bold">No events found matching your search.</p>
                </div>
                <div wire:loading wire:target="search" class="flex flex-col items-center">
                    <div class="w-8 h-8 border-4 border-indigo-600 border-t-transparent rounded-full animate-spin mb-4"></div>
                    <p class="text-slate-500 dark:text-slate-400 font-bold">Searching for "{{ $search }}"...</p>
                </div>
            </div>
        @endif
    </div>
</div>
