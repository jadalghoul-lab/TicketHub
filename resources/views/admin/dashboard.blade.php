<x-layouts::app :title="__('Admin Analytics')">
    <div class="max-w-screen-2xl mx-auto w-full space-y-8">
        
        <!-- Header -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <h1 class="text-3xl font-black text-slate-900 dark:text-white tracking-tight flex items-center gap-3">
                    <span class="w-2 h-8 bg-indigo-500 rounded-full"></span>
                    Platform Overview
                </h1>
                <p class="text-slate-500 dark:text-slate-400 font-medium ml-5">Global metrics and platform health</p>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('admin.events.index') }}" class="bg-purple-600 text-white px-6 py-3 rounded-2xl font-bold shadow-lg shadow-purple-100 dark:shadow-none hover:bg-purple-700 transition-all active:scale-95 flex items-center gap-2">
                    <span class="material-symbols-outlined text-sm">event_note</span>
                    Manage Events
                </a>
                <a href="{{ route('admin.organizers.index') }}" class="bg-indigo-600 text-white px-6 py-3 rounded-2xl font-bold shadow-lg shadow-indigo-100 dark:shadow-none hover:bg-indigo-700 transition-all active:scale-95 flex items-center gap-2">
                    <span class="material-symbols-outlined text-sm">group_add</span>
                    Manage Organizers
                </a>
                <a href="{{ route('admin.export') }}" class="bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-300 px-6 py-3 rounded-2xl font-bold flex items-center gap-2 hover:bg-slate-50 dark:hover:bg-slate-700 transition-all active:scale-95">
                    <span class="material-symbols-outlined text-sm">download</span>
                    Export Report
                </a>
            </div>
        </div>

        <!-- Stats Grid -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <div class="bg-white dark:bg-slate-900 p-8 rounded-[2.5rem] border border-slate-50 dark:border-slate-800 shadow-xl shadow-slate-100/50 dark:shadow-none">
                <div class="w-12 h-12 bg-emerald-50 dark:bg-emerald-900/30 rounded-2xl flex items-center justify-center text-emerald-600 dark:text-emerald-400 mb-6">
                    <span class="material-symbols-outlined">payments</span>
                </div>
                <p class="text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-widest mb-1">Total Revenue</p>
                <h3 class="text-3xl font-black text-slate-900 dark:text-white">€{{ number_format($totalSales, 2) }}</h3>
                <p class="text-xs text-emerald-600 dark:text-emerald-400 font-bold mt-2 flex items-center gap-1">
                    <span class="material-symbols-outlined text-sm">trending_up</span>
                    +12.5% this month
                </p>
            </div>

            <div class="bg-white dark:bg-slate-900 p-8 rounded-[2.5rem] border border-slate-50 dark:border-slate-800 shadow-xl shadow-slate-100/50 dark:shadow-none">
                <div class="w-12 h-12 bg-blue-50 dark:bg-blue-900/30 rounded-2xl flex items-center justify-center text-blue-600 dark:text-blue-400 mb-6">
                    <span class="material-symbols-outlined">confirmation_number</span>
                </div>
                <p class="text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-widest mb-1">Tickets Issued</p>
                <h3 class="text-3xl font-black text-slate-900 dark:text-white">{{ number_format($totalTicketsSold) }}</h3>
                <p class="text-xs text-blue-600 dark:text-blue-400 font-bold mt-2 flex items-center gap-1">
                    <span class="material-symbols-outlined text-sm">check_circle</span>
                    All-time valid
                </p>
            </div>

            <div class="bg-white dark:bg-slate-900 p-8 rounded-[2.5rem] border border-slate-50 dark:border-slate-800 shadow-xl shadow-slate-100/50 dark:shadow-none">
                <div class="w-12 h-12 bg-purple-50 dark:bg-purple-900/30 rounded-2xl flex items-center justify-center text-purple-600 dark:text-purple-400 mb-6">
                    <span class="material-symbols-outlined">event_available</span>
                </div>
                <p class="text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-widest mb-1">Live Events</p>
                <h3 class="text-3xl font-black text-slate-900 dark:text-white">{{ $activeEventsCount }}</h3>
                <p class="text-xs text-purple-600 dark:text-purple-400 font-bold mt-2 flex items-center gap-1">
                    <span class="material-symbols-outlined text-sm">visibility</span>
                    Publicly listed
                </p>
            </div>

            <div class="bg-white dark:bg-slate-900 p-8 rounded-[2.5rem] border border-slate-50 dark:border-slate-800 shadow-xl shadow-slate-100/50 dark:shadow-none">
                <div class="w-12 h-12 bg-amber-50 dark:bg-amber-900/30 rounded-2xl flex items-center justify-center text-amber-600 dark:text-amber-400 mb-6">
                    <span class="material-symbols-outlined">corporate_fare</span>
                </div>
                <p class="text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-widest mb-1">Organizers</p>
                <h3 class="text-3xl font-black text-slate-900 dark:text-white">{{ $totalOrganizersCount }}</h3>
                <p class="text-xs text-amber-600 dark:text-amber-400 font-bold mt-2 flex items-center gap-1">
                    <span class="material-symbols-outlined text-sm">verified</span>
                    Trusted partners
                </p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Recent Sales -->
            <div class="lg:col-span-2 bg-white dark:bg-slate-900 rounded-[2.5rem] border border-slate-50 dark:border-slate-800 shadow-xl shadow-slate-100/50 dark:shadow-none p-8">
                <div class="flex justify-between items-center mb-8">
                    <h2 class="text-xl font-bold text-slate-900 dark:text-white flex items-center gap-2">
                        <span class="material-symbols-outlined text-emerald-500">history</span>
                        Recent Transactions
                    </h2>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-[0.2em] border-b border-slate-50 dark:border-slate-800">
                                <th class="pb-4">Order ID</th>
                                <th class="pb-4">Customer</th>
                                <th class="pb-4">Event</th>
                                <th class="pb-4 text-right">Amount</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50 dark:divide-slate-800">
                            @forelse($recentOrders as $order)
                            <tr class="group hover:bg-slate-50/50 dark:hover:bg-slate-800/50 transition-all">
                                <td class="py-4 text-sm font-bold text-slate-700 dark:text-slate-300">#{{ $order->order_number }}</td>
                                <td class="py-4 text-sm font-medium text-slate-600 dark:text-slate-400">{{ $order->user->name }}</td>
                                <td class="py-4 text-sm font-bold text-slate-900 dark:text-white">{{ $order->event?->title ?? 'N/A' }}</td>
                                <td class="py-4 text-right">
                                    <span class="text-sm font-black text-slate-900 dark:text-white">€{{ number_format($order->total_amount, 2) }}</span>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="4" class="py-8 text-center text-slate-400">No transactions recorded yet.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- New Organizers -->
            <div class="bg-white dark:bg-slate-900 rounded-[2.5rem] border border-slate-50 dark:border-slate-800 shadow-xl shadow-slate-100/50 dark:shadow-none p-8">
                <h3 class="text-lg font-bold mb-6 text-slate-900 dark:text-white flex items-center gap-2">
                    <span class="material-symbols-outlined text-amber-500">person_add</span>
                    New Organizers
                </h3>
                <div class="space-y-6">
                    @forelse($newOrganizers as $organizer)
                    <div class="flex items-center gap-4 group">
                        <div class="w-12 h-12 bg-slate-50 dark:bg-slate-800 rounded-2xl flex items-center justify-center font-bold text-indigo-600 dark:text-indigo-400">
                            {{ substr($organizer->company_name, 0, 1) }}
                        </div>
                        <div class="flex-grow">
                            <h4 class="font-bold text-sm text-slate-900 dark:text-white">{{ $organizer->company_name }}</h4>
                            <p class="text-[10px] text-slate-400">{{ $organizer->user->email }}</p>
                        </div>
                        <span class="text-[10px] font-black text-slate-400 uppercase tracking-tighter">{{ $organizer->created_at->diffForHumans(null, true) }}</span>
                    </div>
                    @empty
                    <p class="text-center text-slate-400 text-sm py-8">No new registrations.</p>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Upcoming Events -->
        <div class="bg-slate-900 dark:bg-slate-950 rounded-[2.5rem] p-10 text-white shadow-2xl overflow-hidden relative">
            <div class="absolute top-0 right-0 w-64 h-64 bg-emerald-500/10 blur-[100px] rounded-full"></div>
            <div class="relative z-10">
                <div class="flex justify-between items-center mb-8">
                    <h2 class="text-2xl font-black flex items-center gap-3">
                        <span class="material-symbols-outlined text-emerald-400">calendar_month</span>
                        Upcoming High-Capacity Events
                    </h2>
                    <a href="{{ route('public.events.index') }}" class="text-xs font-black uppercase tracking-widest text-emerald-400 hover:underline">Full Calendar</a>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6">
                    @foreach($upcomingEvents as $event)
                    <a href="{{ route('public.events.show', $event->slug) }}" class="bg-white/5 border border-white/10 rounded-3xl p-6 hover:bg-white/10 transition-all cursor-pointer block group">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="px-2 py-1 bg-emerald-500/20 text-emerald-400 text-[10px] font-black rounded-lg uppercase">
                                {{ $event->category }}
                            </div>
                        </div>
                        <h4 class="font-bold text-sm mb-1 truncate group-hover:text-emerald-400 transition-colors">{{ $event->title }}</h4>
                        <p class="text-[10px] opacity-60 mb-4">{{ $event->organizer->company_name }}</p>
                        <div class="flex justify-between items-center pt-4 border-t border-white/10">
                            <span class="text-xs font-black">{{ $event->start_date->format('M d') }}</span>
                            <span class="material-symbols-outlined text-emerald-400 text-sm group-hover:translate-x-1 transition-transform">arrow_forward</span>
                        </div>
                    </a>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</x-layouts::app>
