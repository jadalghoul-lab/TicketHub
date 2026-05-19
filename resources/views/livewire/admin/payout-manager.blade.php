<div>
    <div class="mb-6 flex items-center gap-3">
        <h2 class="text-3xl font-black text-slate-900 dark:text-white tracking-tight flex items-center gap-3">
            <span class="w-2 h-8 bg-indigo-500 rounded-full"></span>
            {{ __('Payout Requests Management') }}
        </h2>
    </div>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if (session()->has('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
                    {{ session('success') }}
                </div>
            @endif

            <div class="flex flex-col md:flex-row gap-4 items-center justify-between bg-white dark:bg-slate-900 p-4 rounded-[2rem] border border-slate-50 dark:border-slate-800 shadow-sm mb-6">
                <div class="relative w-full md:w-96">
                    <span class="absolute left-4 top-1/2 -translate-y-1/2 material-symbols-outlined text-slate-400">search</span>
                    <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search organizer..." class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-xl py-3 pl-12 pr-4 text-sm focus:ring-2 focus:ring-indigo-500 transition-all dark:text-white">
                </div>
                <div class="w-full md:w-48">
                    <select wire:model.live="statusFilter" class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-xl py-3 px-4 text-sm font-bold text-slate-600 dark:text-slate-300 focus:ring-2 focus:ring-indigo-500 transition-all cursor-pointer appearance-none">
                        <option value="">All Statuses</option>
                        <option value="pending">Pending</option>
                        <option value="approved">Approved</option>
                        <option value="paid">Paid</option>
                        <option value="rejected">Rejected</option>
                    </select>
                </div>
            </div>

            <div class="bg-white dark:bg-slate-900 rounded-[2.5rem] border border-slate-50 dark:border-slate-800 shadow-xl shadow-slate-100/50 dark:shadow-none p-8">
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-[0.2em] border-b border-slate-50 dark:border-slate-800">
                                <th class="pb-4">ID / Date</th>
                                <th class="pb-4">Organizer</th>
                                <th class="pb-4">Amount</th>
                                <th class="pb-4">Bank Details</th>
                                <th class="pb-4">Status</th>
                                <th class="pb-4 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50 dark:divide-slate-800">
                            @forelse($payouts as $payout)
                                <tr class="group hover:bg-slate-50/50 dark:hover:bg-slate-800/50 transition-all">
                                    <td class="py-4 text-sm font-bold text-slate-700 dark:text-slate-300">
                                        #{{ $payout->id }}<br>
                                        <span class="text-[10px] font-medium text-slate-400">{{ $payout->created_at->format('M d, Y') }}</span>
                                    </td>
                                    <td class="py-4 text-sm font-medium text-slate-900 dark:text-white">
                                        {{ $payout->organizer->user->name }}<br>
                                        <span class="text-[10px] font-bold text-slate-400">{{ $payout->organizer->user->email }}</span>
                                    </td>
                                    <td class="py-4 text-sm font-black text-slate-900 dark:text-white">
                                        €{{ number_format($payout->amount, 2) }}
                                    </td>
                                    <td class="py-4 text-sm text-slate-500 dark:text-slate-400 max-w-xs truncate" title="{{ $payout->bank_details }}">
                                        {{ Str::limit($payout->bank_details, 30) }}
                                    </td>
                                    <td class="py-4">
                                        @if($payout->status === 'pending')
                                            <span class="px-3 py-1 inline-flex text-[10px] font-black rounded-lg bg-amber-100 dark:bg-amber-900/30 text-amber-800 dark:text-amber-400 uppercase tracking-wider border border-amber-200 dark:border-amber-800/50">Pending</span>
                                        @elseif($payout->status === 'approved')
                                            <span class="px-3 py-1 inline-flex text-[10px] font-black rounded-lg bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-400 uppercase tracking-wider border border-blue-200 dark:border-blue-800/50">Approved</span>
                                        @elseif($payout->status === 'paid')
                                            <span class="px-3 py-1 inline-flex text-[10px] font-black rounded-lg bg-emerald-100 dark:bg-emerald-900/30 text-emerald-800 dark:text-emerald-400 uppercase tracking-wider border border-emerald-200 dark:border-emerald-800/50">Paid</span>
                                        @elseif($payout->status === 'rejected')
                                            <span class="px-3 py-1 inline-flex text-[10px] font-black rounded-lg bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-400 uppercase tracking-wider border border-red-200 dark:border-red-800/50">Rejected</span>
                                        @endif
                                    </td>
                                    <td class="py-4 text-right flex gap-2 justify-end">
                                        @if($payout->status === 'pending')
                                            <button wire:click="updateStatus({{ $payout->id }}, 'approved')" class="text-xs font-bold bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400 px-3 py-1.5 rounded-xl hover:bg-blue-100 dark:hover:bg-blue-900/40 transition-colors">Approve</button>
                                            <button wire:click="updateStatus({{ $payout->id }}, 'rejected')" class="text-xs font-bold bg-red-50 dark:bg-red-900/20 text-red-600 dark:text-red-400 px-3 py-1.5 rounded-xl hover:bg-red-100 dark:hover:bg-red-900/40 transition-colors">Reject</button>
                                        @elseif($payout->status === 'approved')
                                            <button wire:click="updateStatus({{ $payout->id }}, 'paid')" class="text-xs font-bold bg-emerald-50 dark:bg-emerald-900/20 text-emerald-600 dark:text-emerald-400 px-3 py-1.5 rounded-xl hover:bg-emerald-100 dark:hover:bg-emerald-900/40 transition-colors flex items-center gap-1">
                                                <span class="material-symbols-outlined text-[14px]">check_circle</span>
                                                Mark Paid
                                            </button>
                                        @endif
                                    </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="py-8 text-center text-sm font-bold text-slate-400 dark:text-slate-500">
                                            <span class="material-symbols-outlined text-4xl mb-2 opacity-50 block">receipt_long</span>
                                            No payout requests found.
                                        </td>
                                    </tr>
                                @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-6 border-t border-slate-50 dark:border-slate-800 pt-6">
                    {{ $payouts->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
