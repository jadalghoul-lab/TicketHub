<div class="relative group" x-data="{ open: false }" @click.away="open = false">
    <button @click="open = !open" 
            class="p-2 transition-all duration-300 hover:bg-slate-100 dark:hover:bg-zinc-800 rounded-xl active:scale-95 flex items-center justify-center relative">
        <span class="material-symbols-outlined text-slate-600 dark:text-zinc-400 group-hover:text-indigo-600 transition-colors">notifications</span>
        
        @if($unreadCount > 0)
            <span class="absolute top-1.5 right-1.5 bg-red-500 w-2 h-2 rounded-full border-2 border-white dark:border-zinc-900 animate-pulse"></span>
        @endif
    </button>

    <!-- Notifications Dropdown -->
    <div x-show="open" 
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 translate-y-4"
         x-transition:enter-end="opacity-100 translate-y-0"
         class="absolute top-full right-0 mt-3 w-80 bg-white dark:bg-zinc-900 rounded-[2rem] shadow-2xl border border-slate-100 dark:border-zinc-800 overflow-hidden z-[70]"
         x-cloak>
        
        <div class="p-5 border-b border-slate-50 dark:border-zinc-800 flex justify-between items-center">
            <h3 class="text-sm font-black text-slate-900 dark:text-white uppercase tracking-tight">Notifications</h3>
            @if($unreadCount > 0)
                <button wire:click="markAllAsRead" class="text-[10px] font-black text-indigo-600 hover:underline uppercase">Mark all read</button>
            @endif
        </div>

        <div class="max-h-96 overflow-y-auto">
            @forelse($notifications as $notification)
                <a href="{{ $notification->data['url'] ?? '#' }}" 
                   class="p-4 border-b border-slate-50 dark:border-zinc-800 flex gap-4 {{ $notification->read_at ? 'opacity-60' : 'bg-indigo-50/30' }} hover:bg-slate-50 dark:hover:bg-zinc-800 transition-all cursor-pointer block">
                    <div class="w-10 h-10 bg-white dark:bg-zinc-800 rounded-xl flex items-center justify-center shadow-sm flex-shrink-0 text-indigo-600">
                        <span class="material-symbols-outlined text-xl">{{ $notification->data['icon'] ?? 'notifications' }}</span>
                    </div>
                    <div class="flex-grow min-w-0">
                        <p class="text-xs font-black text-slate-900 dark:text-white truncate">{{ $notification->data['title'] }}</p>
                        <p class="text-[11px] text-slate-500 dark:text-zinc-500 leading-relaxed mt-1">{{ $notification->data['message'] }}</p>
                        <p class="text-[9px] text-slate-400 mt-2 font-bold">{{ $notification->created_at->diffForHumans() }}</p>
                    </div>
                </a>
            @empty
                <div class="p-12 text-center">
                    <span class="material-symbols-outlined text-4xl text-slate-200 mb-2">notifications_off</span>
                    <p class="text-xs text-slate-400 font-bold">No notifications yet</p>
                </div>
            @endforelse
        </div>

        @if($notifications->count() > 0)
            <div class="p-4 text-center bg-slate-50 dark:bg-zinc-800 rounded-b-[2rem]">
                <a href="#" class="text-[10px] font-black text-slate-500 uppercase tracking-widest hover:text-indigo-600 transition-all">View All Activity</a>
            </div>
        @endif
    </div>
</div>
