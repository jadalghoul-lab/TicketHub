<x-layouts::app :title="__('Customer Dashboard')">
    <div class="max-w-screen-2xl mx-auto w-full p-6 space-y-8 bg-[#fdfdfd] min-h-screen">
        
        <!-- Header -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <h1 class="text-3xl font-black text-slate-900 tracking-tight flex items-center gap-3">
                    <span class="w-2 h-8 bg-indigo-600 rounded-full"></span>
                    My Dashboard
                </h1>
                <p class="text-slate-500 font-medium ml-5">Welcome back, {{ auth()->user()->name }}</p>
            </div>
            <a href="{{ route('public.events.index') }}" class="bg-indigo-600 text-white px-8 py-3 rounded-2xl font-bold shadow-lg shadow-indigo-100 hover:bg-indigo-700 transition-all active:scale-95 flex items-center gap-2">
                <span class="material-symbols-outlined">explore</span>
                Find Events
            </a>
        </div>

        <!-- Stats Grid -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white p-6 rounded-[2rem] border border-slate-50 shadow-xl shadow-slate-100/50">
                <div class="w-12 h-12 bg-indigo-50 rounded-2xl flex items-center justify-center mb-4">
                    <span class="material-symbols-outlined text-indigo-600">confirmation_number</span>
                </div>
                <p class="text-xs font-black text-slate-400 uppercase tracking-widest mb-1">Total Tickets</p>
                <p class="text-3xl font-black text-slate-900">{{ number_format($totalTickets) }}</p>
            </div>

            <div class="bg-white p-6 rounded-[2rem] border border-slate-50 shadow-xl shadow-slate-100/50">
                <div class="w-12 h-12 bg-green-50 rounded-2xl flex items-center justify-center mb-4">
                    <span class="material-symbols-outlined text-green-600">verified</span>
                </div>
                <p class="text-xs font-black text-slate-400 uppercase tracking-widest mb-1">Upcoming Events</p>
                <p class="text-3xl font-black text-slate-900">{{ $upcomingTickets->count() }}</p>
            </div>

            <div class="bg-white p-6 rounded-[2rem] border border-slate-50 shadow-xl shadow-slate-100/50">
                <div class="w-12 h-12 bg-purple-50 rounded-2xl flex items-center justify-center mb-4">
                    <span class="material-symbols-outlined text-purple-600">history</span>
                </div>
                <p class="text-xs font-black text-slate-400 uppercase tracking-widest mb-1">Recent Orders</p>
                <p class="text-3xl font-black text-slate-900">{{ $recentOrders->count() }}</p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Recent Activity Table -->
            <div class="lg:col-span-2 bg-white rounded-[2.5rem] p-8 border border-slate-50 shadow-xl shadow-slate-100/50">
                <div class="flex items-center justify-between mb-8">
                    <h2 class="text-xl font-bold text-slate-900 flex items-center gap-2">
                        <span class="material-symbols-outlined text-indigo-600">shopping_cart</span>
                        Recent Orders
                    </h2>
                    <a href="{{ route('public.tickets.index') }}" class="text-xs font-black text-indigo-600 uppercase tracking-widest hover:underline">View All</a>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">
                                <th class="pb-4">Order #</th>
                                <th class="pb-4">Event</th>
                                <th class="pb-4">Date</th>
                                <th class="pb-4">Amount</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            @forelse($recentOrders as $order)
                            <tr class="group hover:bg-slate-50/50 transition-all">
                                <td class="py-4">
                                    <span class="text-sm font-bold text-slate-700">{{ $order->order_number }}</span>
                                </td>
                                <td class="py-4">
                                    <p class="text-sm font-bold text-slate-900">{{ $order->event->title }}</p>
                                </td>
                                <td class="py-4 text-sm text-slate-500">
                                    {{ $order->created_at->format('M d, Y') }}
                                </td>
                                <td class="py-4">
                                    <span class="text-sm font-black text-slate-900">€{{ number_format($order->total_amount, 2) }}</span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="py-12 text-center text-slate-400 font-bold">No orders found.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Upcoming Tickets Sidebar -->
            <div class="bg-indigo-600 rounded-[2.5rem] p-8 text-white shadow-2xl shadow-indigo-200">
                <h3 class="text-lg font-bold mb-6 flex items-center gap-2">
                    <span class="material-symbols-outlined">confirmation_number</span>
                    Next Events
                </h3>
                <div class="space-y-6">
                    @forelse($upcomingTickets as $ticket)
                    <div class="bg-white/10 rounded-2xl p-4 border border-white/10 group hover:bg-white/20 transition-all cursor-pointer">
                        <p class="text-[10px] font-black uppercase opacity-60 mb-1">{{ $ticket->event->start_date->format('F d') }}</p>
                        <h4 class="font-bold text-sm mb-2">{{ $ticket->event->title }}</h4>
                        <div class="flex justify-between items-center">
                            <span class="text-[10px] font-bold bg-white/20 px-2 py-1 rounded-full">{{ $ticket->ticketType->name }}</span>
                            <a href="{{ route('public.tickets.show', $ticket->uuid) }}" class="text-xs font-black underline underline-offset-4">View Ticket</a>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-8">
                        <span class="material-symbols-outlined text-4xl opacity-30 mb-2">event_busy</span>
                        <p class="text-sm opacity-60">No upcoming tickets.</p>
                    </div>
                    @endforelse
                </div>
                
                <a href="{{ route('public.events.index') }}" class="w-full bg-white text-indigo-600 rounded-2xl py-3 font-bold mt-8 flex items-center justify-center gap-2 text-sm hover:shadow-lg transition-all active:scale-95">
                    Browse More
                </a>
            </div>
        </div>
    </div>
</x-layouts::app>
