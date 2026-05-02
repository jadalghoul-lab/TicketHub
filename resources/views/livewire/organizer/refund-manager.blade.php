<div class="max-w-screen-2xl mx-auto w-full space-y-8">
    <!-- Header -->
    <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-6">
        <div>
            <h1 class="text-3xl font-black text-slate-900 dark:text-white tracking-tight flex items-center gap-3">
                <span class="w-2 h-8 bg-red-600 rounded-full"></span>
                Refund Requests
            </h1>
            <p class="text-slate-500 dark:text-zinc-400 font-medium ml-5">Manage and process customer refund submissions</p>
        </div>
    </div>

    @if (session()->has('success'))
        <div class="bg-green-50 border border-green-100 text-green-700 px-6 py-4 rounded-2xl font-bold flex items-center gap-3">
            <span class="material-symbols-outlined">check_circle</span>
            {{ session('success') }}
        </div>
    @endif

    @if (session()->has('info'))
        <div class="bg-blue-50 border border-blue-100 text-blue-700 px-6 py-4 rounded-2xl font-bold flex items-center gap-3">
            <span class="material-symbols-outlined">info</span>
            {{ session('info') }}
        </div>
    @endif

    <!-- Requests Table -->
    <div class="bg-white dark:bg-zinc-800 rounded-[2.5rem] p-8 border border-slate-50 dark:border-zinc-700 shadow-xl shadow-slate-100/50 dark:shadow-none">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="text-[10px] font-black text-slate-400 dark:text-zinc-500 uppercase tracking-[0.2em] border-b border-slate-50 dark:border-zinc-700">
                        <th class="pb-6">Customer</th>
                        <th class="pb-6">Event & Order</th>
                        <th class="pb-6">Reason</th>
                        <th class="pb-6">Status</th>
                        <th class="pb-6">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50 dark:divide-zinc-700">
                    @forelse($requests as $request)
                    <tr class="group hover:bg-slate-50/50 dark:hover:bg-zinc-700/50 transition-all">
                        <td class="py-6">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-slate-100 dark:bg-zinc-700 flex items-center justify-center text-slate-600 dark:text-zinc-300 font-bold text-xs">
                                    {{ substr($request->user->name, 0, 2) }}
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-slate-900 dark:text-white">{{ $request->user->name }}</p>
                                    <p class="text-[10px] text-slate-400 dark:text-zinc-500 font-bold">{{ $request->user->email }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="py-6">
                            <p class="text-sm font-bold text-slate-900 dark:text-white">{{ $request->order->event->title }}</p>
                            <p class="text-xs text-indigo-600 dark:text-indigo-400 font-black tracking-widest uppercase mt-1">{{ $request->order->order_number }}</p>
                        </td>
                        <td class="py-6">
                            <div class="max-w-xs">
                                <p class="text-xs text-slate-600 dark:text-zinc-400 leading-relaxed italic">"{{ $request->reason }}"</p>
                            </div>
                        </td>
                        <td class="py-6">
                            @php
                                $statusClasses = match($request->status) {
                                    'pending' => 'bg-amber-50 text-amber-600',
                                    'approved' => 'bg-green-50 text-green-600',
                                    'rejected' => 'bg-red-50 text-red-600',
                                    default => 'bg-slate-50 text-slate-600',
                                };
                            @endphp
                            <span class="{{ $statusClasses }} text-[10px] font-black px-4 py-1.5 rounded-full uppercase tracking-widest">
                                {{ $request->status }}
                            </span>
                        </td>
                        <td class="py-6">
                            @if($request->status === 'pending')
                            <div class="flex gap-2">
                                <button wire:click="approve({{ $request->id }})" class="bg-indigo-600 text-white px-4 py-2 rounded-xl text-xs font-bold hover:bg-indigo-700 transition-all active:scale-95 shadow-lg shadow-indigo-100">
                                    Approve
                                </button>
                                <button wire:click="reject({{ $request->id }})" class="bg-white dark:bg-zinc-800 text-red-600 dark:text-red-400 border border-red-100 dark:border-red-900/50 px-4 py-2 rounded-xl text-xs font-bold hover:bg-red-50 dark:hover:bg-red-900/20 transition-all active:scale-95">
                                    Reject
                                </button>
                            </div>
                            @else
                            <span class="text-[10px] font-black text-slate-400 dark:text-zinc-500 uppercase">Processed</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="py-20 text-center">
                            <div class="flex flex-col items-center">
                                <span class="material-symbols-outlined text-6xl text-slate-200 dark:text-zinc-700 mb-4">move_to_inbox</span>
                                <p class="text-slate-400 dark:text-zinc-500 font-bold">No refund requests found.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="mt-8">
            {{ $requests->links() }}
        </div>
    </div>
</div>
