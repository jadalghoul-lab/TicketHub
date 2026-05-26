<x-layouts::app :title="__('Organizer Dashboard')">
    <div class="max-w-screen-2xl mx-auto w-full space-y-8 px-4 py-6 lg:px-8">
        
        <!-- Top Bar & Period Filter -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h1 class="text-2xl lg:text-3xl font-black text-slate-900 dark:text-white tracking-tight flex items-center gap-3">
                    <span class="w-2 h-8 bg-indigo-600 dark:bg-indigo-500 rounded-full"></span>
                    Organizer Hub
                </h1>
                <p class="text-slate-500 dark:text-slate-400 font-medium ml-5 text-sm">{{ $organizer->company_name }} • Performance Overview</p>
            </div>
            
            <div class="flex items-center bg-white dark:bg-slate-900 p-1 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm w-full sm:w-auto">
                @foreach(['today' => 'Today', 'week' => 'Week', 'month' => 'Month'] as $key => $label)
                    <a href="?period={{ $key }}" class="flex-1 sm:flex-none text-center px-4 lg:px-6 py-2.5 rounded-xl text-sm font-bold transition-all {{ $period === $key ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-100 dark:shadow-none' : 'text-slate-400 dark:text-slate-500 hover:text-slate-600 dark:hover:text-slate-200' }}">
                        {{ $label }}
                    </a>
                @endforeach
            </div>
        </div>

        <!-- Quick Stats Grid -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 lg:gap-6">
            <!-- Total Revenue -->
            <div class="bg-white dark:bg-slate-900 p-5 lg:p-6 rounded-2xl lg:rounded-[2rem] border border-slate-100 dark:border-slate-800 shadow-sm dark:shadow-none flex flex-col justify-between" style="background-color: var(--card-bg) !important;">
                <div class="flex items-center justify-between mb-3 lg:mb-4">
                    <div class="w-10 h-10 lg:w-12 lg:h-12 bg-green-50 dark:bg-green-900/20 rounded-xl lg:rounded-2xl flex items-center justify-center">
                        <span class="material-symbols-outlined text-green-600 dark:text-green-400 text-xl lg:text-2xl">payments</span>
                    </div>
                    <span class="text-[10px] font-black text-green-600 dark:text-green-400 bg-green-50 dark:bg-green-900/30 px-2 lg:px-3 py-1 rounded-full uppercase">Live</span>
                </div>
                <div>
                    <p class="text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-widest mb-1">Revenue</p>
                    <p class="text-xl lg:text-3xl font-black text-slate-900 dark:text-white">€{{ number_format($totalRevenue, 2) }}</p>
                </div>
            </div>

            <!-- Tickets Sold -->
            <div class="bg-white dark:bg-slate-900 p-5 lg:p-6 rounded-2xl lg:rounded-[2rem] border border-slate-100 dark:border-slate-800 shadow-sm dark:shadow-none flex flex-col justify-between" style="background-color: var(--card-bg) !important;">
                <div class="flex items-center justify-between mb-3 lg:mb-4">
                    <div class="w-10 h-10 lg:w-12 lg:h-12 bg-indigo-50 dark:bg-indigo-900/20 rounded-xl lg:rounded-2xl flex items-center justify-center">
                        <span class="material-symbols-outlined text-indigo-600 dark:text-indigo-400 text-xl lg:text-2xl">confirmation_number</span>
                    </div>
                    <span class="text-[10px] font-black text-indigo-600 dark:text-indigo-400 bg-indigo-50 dark:bg-indigo-900/30 px-2 lg:px-3 py-1 rounded-full uppercase">{{ $period }}</span>
                </div>
                <div>
                    <p class="text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-widest mb-1">Tickets</p>
                    <p class="text-xl lg:text-3xl font-black text-slate-900 dark:text-white">{{ number_format($totalTickets) }}</p>
                </div>
            </div>

            <!-- Attendance Rate -->
            <div class="bg-white dark:bg-slate-900 p-5 lg:p-6 rounded-2xl lg:rounded-[2rem] border border-slate-100 dark:border-slate-800 shadow-sm dark:shadow-none flex flex-col justify-between" style="background-color: var(--card-bg) !important;">
                <div class="flex items-center justify-between mb-3 lg:mb-4">
                    <div class="w-10 h-10 lg:w-12 lg:h-12 bg-violet-50 dark:bg-violet-900/20 rounded-xl lg:rounded-2xl flex items-center justify-center">
                        <span class="material-symbols-outlined text-violet-600 dark:text-violet-400 text-xl lg:text-2xl">how_to_reg</span>
                    </div>
                    <span class="text-[10px] font-black text-violet-600 dark:text-violet-400 bg-violet-50 dark:bg-violet-900/30 px-2 lg:px-3 py-1 rounded-full uppercase">Rate</span>
                </div>
                <div>
                    <p class="text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-widest mb-1">Attendance</p>
                    <div class="flex items-end gap-2">
                        <p class="text-xl lg:text-3xl font-black text-slate-900 dark:text-white">{{ $attendanceRate }}%</p>
                        <div class="w-full bg-slate-100 dark:bg-slate-800 h-1.5 rounded-full mb-2 overflow-hidden">
                            <div class="bg-violet-500 h-full rounded-full transition-all duration-700" style="width: {{ $attendanceRate }}%"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Active Events -->
            <div class="bg-white dark:bg-slate-900 p-5 lg:p-6 rounded-2xl lg:rounded-[2rem] border border-slate-100 dark:border-slate-800 shadow-sm dark:shadow-none flex flex-col justify-between" style="background-color: var(--card-bg) !important;">
                <div class="flex items-center justify-between mb-3 lg:mb-4">
                    <div class="w-10 h-10 lg:w-12 lg:h-12 bg-amber-50 dark:bg-amber-900/20 rounded-xl lg:rounded-2xl flex items-center justify-center">
                        <span class="material-symbols-outlined text-amber-600 dark:text-amber-400 text-xl lg:text-2xl">event_available</span>
                    </div>
                    <span class="text-[10px] font-black text-amber-600 dark:text-amber-400 bg-amber-50 dark:bg-amber-900/30 px-2 lg:px-3 py-1 rounded-full uppercase">Active</span>
                </div>
                <div>
                    <p class="text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-widest mb-1">Events</p>
                    <p class="text-xl lg:text-3xl font-black text-slate-900 dark:text-white">{{ $events->count() }}</p>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 lg:gap-8">
            <!-- Left Column: Manage Events & Recent Activity -->
            <div class="lg:col-span-2 space-y-6 lg:space-y-8">
                <!-- Top Events Cards -->
                <div class="space-y-4">
                    <h2 class="text-lg lg:text-xl font-bold text-slate-900 dark:text-white flex items-center gap-2">
                        <span class="material-symbols-outlined text-indigo-600 dark:text-indigo-400">stars</span>
                        Top Performing Events
                    </h2>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        @foreach($topEvents as $topEvent)
                        <div class="bg-white dark:bg-slate-900 p-4 lg:p-5 rounded-2xl lg:rounded-3xl border border-slate-100 dark:border-slate-800 shadow-sm flex items-center gap-4 group hover:border-indigo-200 dark:hover:border-indigo-800 transition-all" style="background-color: var(--card-bg) !important;">
                            <div class="w-12 h-12 rounded-xl lg:rounded-2xl bg-slate-900 dark:bg-slate-800 flex items-center justify-center text-white font-black flex-shrink-0">
                                {{ substr($topEvent->title, 0, 1) }}
                            </div>
                            <div class="flex-grow min-w-0">
                                <h4 class="font-bold text-slate-900 dark:text-white truncate text-sm">{{ $topEvent->title }}</h4>
                                <p class="text-xs text-slate-400 dark:text-slate-500 font-bold">{{ $topEvent->tickets_count }} tickets sold</p>
                            </div>
                            <a href="{{ route('organizer.scanner', $topEvent->slug) }}" wire:navigate class="w-9 h-9 bg-indigo-50 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400 rounded-xl flex items-center justify-center group-hover:bg-indigo-600 group-hover:text-white transition-all flex-shrink-0">
                                <span class="material-symbols-outlined text-sm">qr_code_scanner</span>
                            </a>
                        </div>
                        @endforeach
                    </div>
                </div>

                <!-- Recent Orders Table -->
                <div class="bg-white dark:bg-slate-900 rounded-2xl lg:rounded-[2.5rem] p-5 lg:p-8 border border-slate-100 dark:border-slate-800 shadow-sm dark:shadow-none" style="background-color: var(--card-bg) !important;">
                    <div class="flex items-center justify-between mb-6 lg:mb-8">
                        <h2 class="text-lg lg:text-xl font-bold text-slate-900 dark:text-white flex items-center gap-2">
                            <span class="material-symbols-outlined text-indigo-600 dark:text-indigo-400">history</span>
                            Recent Sales
                        </h2>
                        <a href="{{ route('organizer.events.index') }}" wire:navigate class="text-xs font-black text-indigo-600 dark:text-indigo-400 uppercase tracking-widest hover:underline flex items-center gap-1">
                            View All
                            <span class="material-symbols-outlined text-xs">arrow_forward</span>
                        </a>
                    </div>
                    
                    <div class="overflow-x-auto -mx-5 lg:-mx-8 px-5 lg:px-8">
                        <table class="w-full text-left min-w-[500px]">
                            <thead>
                                <tr class="text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-[0.2em] border-b border-slate-100 dark:border-slate-800">
                                    <th class="pb-4">Customer</th>
                                    <th class="pb-4">Event</th>
                                    <th class="pb-4">Amount</th>
                                    <th class="pb-4">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-50 dark:divide-slate-800">
                                @foreach($recentOrders as $order)
                                <tr class="group hover:bg-slate-50/50 dark:hover:bg-slate-800/50 transition-all cursor-pointer" onclick="window.location='{{ route('organizer.events.tickets', $order->event->slug) }}'">
                                    <td class="py-3 lg:py-4">
                                        <div class="flex items-center gap-3">
                                            <div class="w-8 h-8 rounded-full bg-indigo-50 dark:bg-indigo-900/30 flex items-center justify-center text-indigo-600 dark:text-indigo-400 font-bold text-[10px] flex-shrink-0">
                                                {{ substr($order->user->name, 0, 2) }}
                                            </div>
                                            <span class="text-sm font-bold text-slate-700 dark:text-slate-300 truncate">{{ $order->user->name }}</span>
                                        </div>
                                    </td>
                                    <td class="py-3 lg:py-4">
                                        <p class="text-sm font-bold text-slate-900 dark:text-white truncate max-w-[120px] lg:max-w-[160px]">{{ $order->event->title }}</p>
                                        <p class="text-[10px] text-slate-400 dark:text-slate-500 font-bold">{{ $order->created_at->diffForHumans() }}</p>
                                    </td>
                                    <td class="py-3 lg:py-4">
                                        <span class="text-sm font-black text-slate-900 dark:text-white">€{{ number_format($order->total_amount, 2) }}</span>
                                    </td>
                                    <td class="py-3 lg:py-4">
                                        @php
                                            $statusColors = [
                                                'paid'      => 'bg-green-50 dark:bg-green-900/30 text-green-600 dark:text-green-400',
                                                'pending'   => 'bg-amber-50 dark:bg-amber-900/30 text-amber-600 dark:text-amber-400',
                                                'failed'    => 'bg-red-50 dark:bg-red-900/30 text-red-600 dark:text-red-400',
                                                'refunded'  => 'bg-slate-100 dark:bg-slate-800 text-slate-500 dark:text-slate-400',
                                            ];
                                            $color = $statusColors[$order->status] ?? 'bg-slate-100 dark:bg-slate-800 text-slate-500 dark:text-slate-400';
                                        @endphp
                                        <span class="{{ $color }} text-[10px] font-black px-2 lg:px-3 py-1 rounded-full uppercase">{{ ucfirst($order->status) }}</span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Right Column: Schedule & Quick Actions -->
            <div class="space-y-6 lg:space-y-8">
                <!-- Upcoming Schedule -->
                <div class="bg-indigo-600 dark:bg-indigo-900/50 rounded-2xl lg:rounded-[2.5rem] p-6 lg:p-8 text-white shadow-xl shadow-indigo-200/50 dark:shadow-none border border-indigo-500 dark:border-indigo-800">
                    <h3 class="text-base lg:text-lg font-bold mb-5 lg:mb-6 flex items-center gap-2">
                        <span class="material-symbols-outlined">calendar_month</span>
                        Upcoming Schedule
                    </h3>
                    <div class="space-y-4 lg:space-y-6">
                        @forelse($upcomingEvents as $upcoming)
                        <a href="{{ route('organizer.events.tickets', $upcoming->slug) }}" wire:navigate class="flex gap-4 items-start border-l-2 border-white/20 pl-4 py-1 hover:border-white/60 transition-all group">
                            <div class="text-center min-w-[2.5rem]">
                                <p class="text-xs font-black uppercase opacity-70">{{ $upcoming->start_date->format('M') }}</p>
                                <p class="text-xl font-black">{{ $upcoming->start_date->format('d') }}</p>
                            </div>
                            <div class="flex-grow min-w-0">
                                <h4 class="font-bold text-sm leading-tight group-hover:underline truncate">{{ $upcoming->title }}</h4>
                                <p class="text-[10px] font-medium opacity-70">{{ $upcoming->city }} • {{ $upcoming->start_date->diffForHumans() }}</p>
                            </div>
                            <span class="material-symbols-outlined text-white/40 group-hover:text-white text-sm transition-colors flex-shrink-0">arrow_forward_ios</span>
                        </a>
                        @empty
                        <p class="text-sm opacity-70">No upcoming events scheduled.</p>
                        @endforelse
                    </div>
                    
                    <a href="{{ route('organizer.events.index') }}" wire:navigate class="w-full bg-white/10 hover:bg-white/20 transition-all text-white rounded-2xl py-3 font-bold mt-6 lg:mt-8 flex items-center justify-center gap-2 text-sm active:scale-95">
                        <span class="material-symbols-outlined text-sm">add</span>
                        Create New Event
                    </a>
                </div>

                <!-- Quick Scanner Shortcut -->
                <div class="bg-white dark:bg-slate-900 rounded-2xl lg:rounded-[2.5rem] p-6 lg:p-8 border border-slate-100 dark:border-slate-800 shadow-sm dark:shadow-none" style="background-color: var(--card-bg) !important;">
                    <h3 class="font-bold text-slate-900 dark:text-white mb-4 lg:mb-6 flex items-center gap-2">
                        <span class="material-symbols-outlined text-indigo-600 dark:text-indigo-400">qr_code_scanner</span>
                        Quick Scanner
                    </h3>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mb-4 lg:mb-6">Select an event to launch the scanning interface for guest check-in.</p>
                    
                    <div class="space-y-3">
                        @forelse($events->take(3) as $event)
                        <a href="{{ route('organizer.scanner', $event->slug) }}" wire:navigate class="flex items-center justify-between p-3 lg:p-4 bg-slate-50 dark:bg-slate-800 rounded-xl lg:rounded-2xl group hover:bg-indigo-600 dark:hover:bg-indigo-600 transition-all active:scale-95">
                            <span class="text-xs font-bold text-slate-700 dark:text-slate-300 group-hover:text-white transition-colors truncate">{{ $event->title }}</span>
                            <span class="material-symbols-outlined text-slate-400 dark:text-slate-500 group-hover:text-white text-sm flex-shrink-0">arrow_forward_ios</span>
                        </a>
                        @empty
                        <p class="text-xs text-slate-400 dark:text-slate-500 text-center py-4">No events yet. <a href="{{ route('organizer.events.index') }}" wire:navigate class="text-indigo-600 dark:text-indigo-400 hover:underline font-bold">Create one</a></p>
                        @endforelse
                    </div>
                </div>

        </div>

        </div>

        <!-- Premium Live Entry Feed -->
        <div class="bg-white dark:bg-slate-900 rounded-2xl lg:rounded-[2.5rem] border border-slate-100 dark:border-slate-800 shadow-sm dark:shadow-none overflow-hidden" style="background-color: var(--card-bg) !important;">
            <div class="p-5 lg:p-8 border-b border-slate-100 dark:border-slate-800 flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-slate-50/50 dark:bg-slate-800/30">
                <div>
                    <h3 class="text-lg lg:text-xl font-black text-slate-900 dark:text-white flex items-center gap-2">
                        <span class="material-symbols-outlined text-green-600 dark:text-green-400">sensors</span>
                        Real-time Entry Monitor
                    </h3>
                    <p class="text-xs text-slate-400 dark:text-slate-500 font-bold uppercase tracking-widest mt-1">Live tracking across all active events</p>
                </div>
                <div class="flex items-center gap-2 px-3 lg:px-4 py-2 bg-green-50 dark:bg-green-900/20 rounded-full self-start sm:self-auto">
                    <span class="flex h-2 w-2 relative">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-green-500"></span>
                    </span>
                    <span class="text-[10px] font-black text-green-600 dark:text-green-400 uppercase tracking-widest">System Live</span>
                </div>
            </div>
            
            <!-- Mobile Cards View -->
            <div class="lg:hidden divide-y divide-slate-100 dark:divide-slate-800">
                @forelse($recentScans as $scan)
                <div class="p-4 flex items-center gap-4">
                    <div class="w-10 h-10 rounded-xl bg-indigo-50 dark:bg-indigo-900/30 flex items-center justify-center text-indigo-600 dark:text-indigo-400 font-black text-xs flex-shrink-0">
                        {{ substr($scan->user?->name ?? 'G', 0, 1) }}
                    </div>
                    <div class="flex-grow min-w-0">
                        <p class="text-sm font-black text-slate-900 dark:text-white truncate">{{ $scan->user?->name ?? 'Guest' }}</p>
                        <p class="text-xs text-slate-400 dark:text-slate-500 truncate">{{ $scan->event->title }}</p>
                    </div>
                    <div class="text-right flex-shrink-0">
                        <span class="inline-flex items-center gap-1 px-2 py-1 bg-green-50 dark:bg-green-900/30 text-green-600 dark:text-green-400 text-[10px] font-black rounded-full uppercase">
                            <span class="w-1 h-1 bg-green-500 rounded-full animate-pulse"></span>
                            ✓
                        </span>
                        <p class="text-[10px] text-slate-400 dark:text-slate-500 mt-1">{{ $scan->scanned_at?->format('H:i') ?? '--:--' }}</p>
                    </div>
                </div>
                @empty
                <div class="py-16 text-center">
                    <span class="material-symbols-outlined text-5xl text-slate-200 dark:text-slate-700 mb-3 block">qr_code_scanner</span>
                    <p class="text-slate-400 dark:text-slate-600 font-black uppercase tracking-widest text-xs">Waiting for your first entry...</p>
                </div>
                @endforelse
            </div>

            <!-- Desktop Table View -->
            <div class="hidden lg:block overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-[0.2em] border-b border-slate-100 dark:border-slate-800">
                            <th class="px-8 py-6">Attendee</th>
                            <th class="px-8 py-6">Event Details</th>
                            <th class="px-8 py-6">Ticket Reference</th>
                            <th class="px-8 py-6">Check-in Time</th>
                            <th class="px-8 py-6 text-right">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50 dark:divide-slate-800">
                        @forelse($recentScans as $scan)
                        <tr class="group hover:bg-slate-50/50 dark:hover:bg-slate-800/30 transition-all">
                            <td class="px-8 py-5">
                                <div class="flex items-center gap-4">
                                    <div class="w-10 h-10 rounded-xl bg-indigo-50 dark:bg-indigo-900/30 flex items-center justify-center text-indigo-600 dark:text-indigo-400 font-black text-xs group-hover:scale-110 transition-transform">
                                        {{ substr($scan->user?->name ?? 'G', 0, 1) }}
                                    </div>
                                    <div>
                                        <p class="text-sm font-black text-slate-900 dark:text-white">{{ $scan->user?->name ?? 'Guest' }}</p>
                                        <p class="text-[10px] text-slate-400 dark:text-slate-500 font-bold">{{ $scan->user?->email ?? 'No email associated' }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-8 py-5">
                                <p class="text-sm font-bold text-slate-900 dark:text-white truncate max-w-[200px]">{{ $scan->event->title }}</p>
                                <p class="text-[10px] text-indigo-600 dark:text-indigo-400 font-black uppercase tracking-tighter">{{ $scan->ticketType->name }}</p>
                            </td>
                            <td class="px-8 py-5">
                                <span class="font-mono text-xs font-bold text-slate-500 dark:text-slate-400">#{{ $scan->ticket_number }}</span>
                            </td>
                            <td class="px-8 py-5">
                                <div class="flex flex-col">
                                    <span class="text-sm font-black text-slate-900 dark:text-white">{{ $scan->scanned_at?->format('H:i:s') ?? '--:--:--' }}</span>
                                    <span class="text-[10px] text-slate-400 dark:text-slate-500 font-bold">{{ $scan->scanned_at?->diffForHumans() ?? 'N/A' }}</span>
                                </div>
                            </td>
                            <td class="px-8 py-5 text-right">
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-green-50 dark:bg-green-900/30 text-green-600 dark:text-green-400 text-[10px] font-black rounded-full uppercase">
                                    <span class="w-1 h-1 bg-green-500 rounded-full animate-pulse"></span>
                                    Verified
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-8 py-20 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <span class="material-symbols-outlined text-6xl text-slate-200 dark:text-slate-700 mb-4">qr_code_scanner</span>
                                    <p class="text-slate-300 dark:text-slate-700 font-black uppercase tracking-widest text-xs">Waiting for your first entry...</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-layouts::app>
