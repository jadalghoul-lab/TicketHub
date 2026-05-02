<div class="relative group">
    <a href="{{ route('public.tickets.index') }}" 
       class="p-2 transition-all duration-300 hover:bg-indigo-50 dark:hover:bg-zinc-800 rounded-xl active:scale-95 flex items-center justify-center relative">
        <span class="material-symbols-outlined text-slate-600 dark:text-zinc-400 group-hover:text-indigo-600 transition-colors">shopping_cart</span>
        
        @if($ticketCount > 0)
            <span class="absolute -top-1 -right-1 bg-indigo-600 text-white text-[10px] font-black w-5 h-5 flex items-center justify-center rounded-full border-2 border-white dark:border-zinc-900 animate-in zoom-in duration-300">
                {{ $ticketCount }}
            </span>
        @endif
    </a>

    <!-- Tooltip / Mini-Dropdown -->
    <div class="absolute top-full right-0 mt-3 w-48 bg-white dark:bg-zinc-900 rounded-2xl shadow-2xl border border-slate-100 dark:border-zinc-800 p-4 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 translate-y-2 group-hover:translate-y-0 z-[60]">
        <p class="text-xs font-bold text-slate-900 dark:text-white mb-1">
            @if($ticketCount > 0)
                You have {{ $ticketCount }} ticket{{ $ticketCount > 1 ? 's' : '' }}
            @else
                No tickets yet
            @endif
        </p>
        <p class="text-[10px] text-slate-400 font-medium">
            {{ $ticketCount > 0 ? 'Click to view your collection' : 'Find your next experience' }}
        </p>
        <div class="mt-3 pt-3 border-t border-slate-50 dark:border-zinc-800">
            <a href="{{ route('public.tickets.index') }}" class="text-[10px] font-black text-indigo-600 uppercase tracking-widest hover:underline">View My Tickets</a>
        </div>
    </div>
</div>
