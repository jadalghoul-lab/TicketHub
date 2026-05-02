<x-layouts::app :title="__('Organizer Dashboard')">
    <div class="max-w-screen-2xl mx-auto w-full space-y-8">
        
        <!-- Top Bar & Period Filter -->
        <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-6">
            <div>
                <h1 class="text-3xl font-black text-slate-900 dark:text-white tracking-tight flex items-center gap-3">
                    <span class="w-2 h-8 bg-indigo-600 rounded-full"></span>
                    Organizer Hub
                </h1>
                <p class="text-slate-500 dark:text-zinc-400 font-medium ml-5">{{ $organizer->company_name }} • Performance Overview</p>
            </div>
            
            <div class="flex items-center bg-white dark:bg-zinc-800 p-1 rounded-2xl border border-slate-100 dark:border-zinc-700 shadow-sm">
                @foreach(['today' => 'Today', 'week' => 'Week', 'month' => 'Month'] as $key => $label)
                    <a href="?period={{ $key }}" class="px-6 py-2.5 rounded-xl text-sm font-bold transition-all {{ $period === $key ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-100 dark:shadow-none' : 'text-slate-400 dark:text-zinc-400 hover:text-slate-600 dark:hover:text-zinc-200' }}">
                        {{ $label }}
                    </a>
                @endforeach
            </div>
        </div>

        <!-- Quick Stats Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Total Revenue -->
            <div class="bg-white dark:bg-zinc-800 p-6 rounded-[2rem] border border-slate-50 dark:border-zinc-700 shadow-xl shadow-slate-100/50 dark:shadow-none flex flex-col justify-between">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-green-50 rounded-2xl flex items-center justify-center">
                        <span class="material-symbols-outlined text-green-600">payments</span>
                    </div>
                    <span class="text-[10px] font-black text-green-600 dark:text-green-400 bg-green-50 dark:bg-green-900/30 px-3 py-1 rounded-full uppercase">Live</span>
                </div>
                <div>
                    <p class="text-xs font-black text-slate-400 dark:text-zinc-500 uppercase tracking-widest mb-1">Total Revenue</p>
                    <p class="text-3xl font-black text-slate-900 dark:text-white">€{{ number_format($totalRevenue, 2) }}</p>
                </div>
            </div>

            <!-- Tickets Sold -->
            <div class="bg-white dark:bg-zinc-800 p-6 rounded-[2rem] border border-slate-50 dark:border-zinc-700 shadow-xl shadow-slate-100/50 dark:shadow-none flex flex-col justify-between">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-indigo-50 rounded-2xl flex items-center justify-center">
                        <span class="material-symbols-outlined text-indigo-600">confirmation_number</span>
                    </div>
                    <span class="text-[10px] font-black text-indigo-600 dark:text-indigo-400 bg-indigo-50 dark:bg-indigo-900/30 px-3 py-1 rounded-full uppercase">{{ $period }}</span>
                </div>
                <div>
                    <p class="text-xs font-black text-slate-400 dark:text-zinc-500 uppercase tracking-widest mb-1">Tickets Sold</p>
                    <p class="text-3xl font-black text-slate-900 dark:text-white">{{ number_format($totalTickets) }}</p>
                </div>
            </div>

            <!-- Attendance Rate -->
            <div class="bg-white dark:bg-zinc-800 p-6 rounded-[2rem] border border-slate-50 dark:border-zinc-700 shadow-xl shadow-slate-100/50 dark:shadow-none flex flex-col justify-between">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-purple-50 rounded-2xl flex items-center justify-center">
                        <span class="material-symbols-outlined text-purple-600">how_to_reg</span>
                    </div>
                    <span class="text-[10px] font-black text-purple-600 dark:text-purple-400 bg-purple-50 dark:bg-purple-900/30 px-3 py-1 rounded-full uppercase">Attendance</span>
                </div>
                <div>
                    <p class="text-xs font-black text-slate-400 dark:text-zinc-500 uppercase tracking-widest mb-1">Average Rate</p>
                    <div class="flex items-end gap-3">
                        <p class="text-3xl font-black text-slate-900 dark:text-white">{{ $attendanceRate }}%</p>
                        <div class="w-full bg-slate-100 h-2 rounded-full mb-2 overflow-hidden">
                            <div class="bg-purple-500 h-full rounded-full" style="width: {{ $attendanceRate }}%"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Active Events -->
            <div class="bg-white dark:bg-zinc-800 p-6 rounded-[2rem] border border-slate-50 dark:border-zinc-700 shadow-xl shadow-slate-100/50 dark:shadow-none flex flex-col justify-between">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-amber-50 rounded-2xl flex items-center justify-center">
                        <span class="material-symbols-outlined text-amber-600">event_available</span>
                    </div>
                    <span class="text-[10px] font-black text-amber-600 dark:text-amber-400 bg-amber-50 dark:bg-amber-900/30 px-3 py-1 rounded-full uppercase">Active</span>
                </div>
                <div>
                    <p class="text-xs font-black text-slate-400 dark:text-zinc-500 uppercase tracking-widest mb-1">Total Events</p>
                    <p class="text-3xl font-black text-slate-900 dark:text-white">{{ $events->count() }}</p>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Left Column: Manage Events & Recent Activity -->
            <div class="lg:col-span-2 space-y-8">
                <!-- Top Events Cards -->
                <div class="space-y-4">
                    <h2 class="text-xl font-bold text-slate-900 dark:text-white flex items-center gap-2">
                        <span class="material-symbols-outlined text-indigo-600 dark:text-indigo-400">stars</span>
                        Top Performing Events
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach($topEvents as $topEvent)
                        <div class="bg-white dark:bg-zinc-800 p-5 rounded-3xl border border-slate-100 dark:border-zinc-700 shadow-sm flex items-center gap-4 group hover:border-indigo-100 dark:hover:border-indigo-900 transition-all">
                            <div class="w-14 h-14 rounded-2xl bg-slate-900 dark:bg-zinc-900 flex items-center justify-center text-white font-black">
                                {{ substr($topEvent->title, 0, 1) }}
                            </div>
                            <div class="flex-grow">
                                <h4 class="font-bold text-slate-900 dark:text-white truncate w-40">{{ $topEvent->title }}</h4>
                                <p class="text-xs text-slate-400 dark:text-zinc-400 font-bold">{{ $topEvent->tickets_count }} tickets sold</p>
                            </div>
                            <a href="{{ route('organizer.scanner', $topEvent->slug) }}" class="w-10 h-10 bg-indigo-50 text-indigo-600 rounded-xl flex items-center justify-center group-hover:bg-indigo-600 group-hover:text-white transition-all">
                                <span class="material-symbols-outlined text-sm">qr_code_scanner</span>
                            </a>
                        </div>
                        @endforeach
                    </div>
                </div>

                <!-- Recent Orders Table -->
                <div class="bg-white dark:bg-zinc-800 rounded-[2.5rem] p-8 border border-slate-50 dark:border-zinc-700 shadow-xl shadow-slate-100/50 dark:shadow-none">
                    <div class="flex items-center justify-between mb-8">
                        <h2 class="text-xl font-bold text-slate-900 dark:text-white flex items-center gap-2">
                            <span class="material-symbols-outlined text-indigo-600 dark:text-indigo-400">history</span>
                            Recent Sales
                        </h2>
                        <button class="text-xs font-black text-indigo-600 dark:text-indigo-400 uppercase tracking-widest hover:underline">View All</button>
                    </div>
                    
                    <div class="overflow-x-auto">
                        <table class="w-full text-left">
                            <thead>
                                <tr class="text-[10px] font-black text-slate-400 dark:text-zinc-500 uppercase tracking-[0.2em] border-b border-slate-50 dark:border-zinc-700">
                                    <th class="pb-4">Customer</th>
                                    <th class="pb-4">Event</th>
                                    <th class="pb-4">Amount</th>
                                    <th class="pb-4">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-50 dark:divide-zinc-700">
                                @foreach($recentOrders as $order)
                                <tr class="group hover:bg-slate-50/50 dark:hover:bg-zinc-700/50 transition-all">
                                    <td class="py-4">
                                        <div class="flex items-center gap-3">
                                            <div class="w-8 h-8 rounded-full bg-indigo-50 dark:bg-indigo-900/30 flex items-center justify-center text-indigo-600 dark:text-indigo-400 font-bold text-[10px]">
                                                {{ substr($order->user->name, 0, 2) }}
                                            </div>
                                            <span class="text-sm font-bold text-slate-700 dark:text-zinc-300">{{ $order->user->name }}</span>
                                        </div>
                                    </td>
                                    <td class="py-4">
                                        <p class="text-sm font-bold text-slate-900 dark:text-white truncate w-32">{{ $order->event->title }}</p>
                                        <p class="text-[10px] text-slate-400 dark:text-zinc-500 font-bold">{{ $order->created_at->diffForHumans() }}</p>
                                    </td>
                                    <td class="py-4">
                                        <span class="text-sm font-black text-slate-900 dark:text-white">€{{ number_format($order->total_amount, 2) }}</span>
                                    </td>
                                    <td class="py-4">
                                        <span class="bg-green-50 text-green-600 text-[10px] font-black px-3 py-1 rounded-full uppercase">Paid</span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Right Column: Schedule & Quick Actions -->
            <div class="space-y-8">
                <!-- Upcoming Schedule -->
                <div class="bg-indigo-600 dark:bg-indigo-900/50 rounded-[2.5rem] p-8 text-white shadow-2xl shadow-indigo-200 dark:shadow-none border border-indigo-500 dark:border-indigo-800">
                    <h3 class="text-lg font-bold mb-6 flex items-center gap-2">
                        <span class="material-symbols-outlined">calendar_month</span>
                        Upcoming Schedule
                    </h3>
                    <div class="space-y-6">
                        @forelse($upcomingEvents as $upcoming)
                        <div class="flex gap-4 items-start border-l-2 border-white/20 pl-4 py-1">
                            <div class="text-center">
                                <p class="text-xs font-black uppercase opacity-70">{{ $upcoming->start_date->format('M') }}</p>
                                <p class="text-xl font-black">{{ $upcoming->start_date->format('d') }}</p>
                            </div>
                            <div>
                                <h4 class="font-bold text-sm leading-tight">{{ $upcoming->title }}</h4>
                                <p class="text-[10px] font-medium opacity-70">{{ $upcoming->city }} • {{ $upcoming->start_date->diffForHumans() }}</p>
                            </div>
                        </div>
                        @empty
                        <p class="text-sm opacity-70">No upcoming events scheduled.</p>
                        @endforelse
                    </div>
                    
                    <a href="{{ route('organizer.events.index', ['create' => 1]) }}" wire:navigate class="w-full bg-white/10 hover:bg-white/20 transition-all text-white rounded-2xl py-3 font-bold mt-8 flex items-center justify-center gap-2 text-sm">
                        <span class="material-symbols-outlined text-sm">add</span>
                        Create New Event
                    </a>
                </div>

                <!-- Active Scanner Shortcut -->
                <div class="bg-white dark:bg-zinc-800 rounded-[2.5rem] p-8 border border-slate-50 dark:border-zinc-700 shadow-xl shadow-slate-100/50 dark:shadow-none">
                    <h3 class="font-bold text-slate-900 dark:text-white mb-6 flex items-center gap-2">
                        <span class="material-symbols-outlined text-indigo-600 dark:text-indigo-400">qr_code_scanner</span>
                        Quick Scanner
                    </h3>
                    <p class="text-xs text-slate-500 mb-6">Select an event to launch the scanning interface for guest check-in.</p>
                    
                    <div class="space-y-3">
                        @foreach($events->take(3) as $event)
                        <a href="{{ route('organizer.scanner', $event->slug) }}" class="flex items-center justify-between p-4 bg-slate-50 dark:bg-zinc-700/50 rounded-2xl group hover:bg-indigo-600 dark:hover:bg-indigo-600 transition-all">
                            <span class="text-xs font-bold text-slate-700 dark:text-zinc-300 group-hover:text-white transition-colors truncate w-40">{{ $event->title }}</span>
                            <span class="material-symbols-outlined text-slate-400 dark:text-zinc-500 group-hover:text-white text-sm">arrow_forward_ios</span>
                        </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts::app>
