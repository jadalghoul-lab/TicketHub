<div class="max-w-screen-xl mx-auto px-4 py-6 lg:px-8 space-y-6">

    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl lg:text-3xl font-black text-slate-900 dark:text-white tracking-tight flex items-center gap-3">
                <span class="w-2 h-8 bg-indigo-600 dark:bg-indigo-500 rounded-full"></span>
                Payouts
            </h1>
            <p class="text-slate-500 dark:text-slate-400 font-medium ml-5 text-sm">Manage your payout requests</p>
        </div>

        <flux:modal.trigger name="request-payout">
            <button class="flex items-center gap-2 bg-indigo-600 dark:bg-indigo-500 text-white px-5 py-2.5 rounded-xl font-bold text-sm hover:bg-indigo-700 dark:hover:bg-indigo-600 transition-all active:scale-95 shadow-lg shadow-indigo-100 dark:shadow-none">
                <span class="material-symbols-outlined text-xl">add</span>
                Request Payout
            </button>
        </flux:modal.trigger>
    </div>

    <!-- Balance Card -->
    <div class="bg-indigo-600 dark:bg-indigo-900/50 rounded-2xl lg:rounded-[2rem] p-6 lg:p-8 text-white shadow-xl shadow-indigo-200/50 dark:shadow-none border border-indigo-500 dark:border-indigo-800 relative overflow-hidden">
        <div class="absolute -right-12 -top-12 w-40 h-40 bg-white/10 rounded-full blur-2xl"></div>
        <div class="absolute -left-12 -bottom-12 w-40 h-40 bg-white/10 rounded-full blur-2xl"></div>
        <div class="relative flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <div class="flex items-center gap-2 mb-1">
                    <span class="material-symbols-outlined text-white/70">account_balance_wallet</span>
                    <p class="text-sm font-black uppercase tracking-widest text-white/70">Available Balance</p>
                </div>
                <p class="text-4xl lg:text-5xl font-black tracking-tight">€{{ number_format($availableBalance, 2) }}</p>
            </div>
            <div class="bg-white/10 backdrop-blur-md border border-white/10 rounded-2xl p-4 text-sm text-white/80 max-w-xs">
                <span class="material-symbols-outlined text-sm align-middle mr-1">info</span>
                Total ticket sales minus payouts already requested or processed.
            </div>
        </div>
    </div>

    <!-- Payout History -->
    <div class="bg-white dark:bg-slate-900 rounded-2xl lg:rounded-[2rem] border border-slate-100 dark:border-slate-800 shadow-sm dark:shadow-none overflow-hidden">
        <div class="px-5 lg:px-8 py-5 border-b border-slate-100 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-800/30">
            <h2 class="text-lg font-black text-slate-900 dark:text-white flex items-center gap-2">
                <span class="material-symbols-outlined text-indigo-600 dark:text-indigo-400">receipt_long</span>
                Payout History
            </h2>
        </div>

        @if($payouts->count() > 0)
            <!-- Mobile Card View -->
            <div class="lg:hidden divide-y divide-slate-100 dark:divide-slate-800">
                @foreach($payouts as $payout)
                <div class="p-4 flex items-center gap-4">
                    <div class="w-10 h-10 rounded-xl bg-indigo-50 dark:bg-indigo-900/30 flex items-center justify-center flex-shrink-0">
                        <span class="material-symbols-outlined text-indigo-600 dark:text-indigo-400 text-xl">payments</span>
                    </div>
                    <div class="flex-grow min-w-0">
                        <p class="text-sm font-black text-slate-900 dark:text-white">€{{ number_format($payout->amount, 2) }}</p>
                        <p class="text-xs text-slate-400 dark:text-slate-500">{{ $payout->created_at->format('M d, Y') }}</p>
                        <p class="text-xs text-slate-500 dark:text-slate-400 truncate">{{ Str::limit($payout->bank_details, 30) }}</p>
                    </div>
                    <div class="flex-shrink-0">
                        @if($payout->status === 'pending')
                            <span class="text-[10px] font-black px-2.5 py-1 rounded-full bg-amber-50 dark:bg-amber-900/30 text-amber-600 dark:text-amber-400 uppercase">Pending</span>
                        @elseif($payout->status === 'approved')
                            <span class="text-[10px] font-black px-2.5 py-1 rounded-full bg-indigo-50 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400 uppercase">Approved</span>
                        @elseif($payout->status === 'paid')
                            <span class="text-[10px] font-black px-2.5 py-1 rounded-full bg-green-50 dark:bg-green-900/30 text-green-600 dark:text-green-400 uppercase">Paid</span>
                        @elseif($payout->status === 'rejected')
                            <span class="text-[10px] font-black px-2.5 py-1 rounded-full bg-red-50 dark:bg-red-900/30 text-red-600 dark:text-red-400 uppercase">Rejected</span>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Desktop Table View -->
            <div class="hidden lg:block overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-[0.2em] border-b border-slate-100 dark:border-slate-800">
                            <th class="px-8 py-5">Date</th>
                            <th class="px-8 py-5">Amount</th>
                            <th class="px-8 py-5">Status</th>
                            <th class="px-8 py-5">Bank Details</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50 dark:divide-slate-800">
                        @foreach($payouts as $payout)
                        <tr class="group hover:bg-slate-50/50 dark:hover:bg-slate-800/30 transition-all">
                            <td class="px-8 py-4 text-sm text-slate-500 dark:text-slate-400">
                                {{ $payout->created_at->format('M d, Y H:i') }}
                            </td>
                            <td class="px-8 py-4">
                                <span class="text-sm font-black text-slate-900 dark:text-white">€{{ number_format($payout->amount, 2) }}</span>
                            </td>
                            <td class="px-8 py-4">
                                @if($payout->status === 'pending')
                                    <span class="text-[10px] font-black px-3 py-1 rounded-full bg-amber-50 dark:bg-amber-900/30 text-amber-600 dark:text-amber-400 uppercase">Pending</span>
                                @elseif($payout->status === 'approved')
                                    <span class="text-[10px] font-black px-3 py-1 rounded-full bg-indigo-50 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400 uppercase">Approved</span>
                                @elseif($payout->status === 'paid')
                                    <span class="text-[10px] font-black px-3 py-1 rounded-full bg-green-50 dark:bg-green-900/30 text-green-600 dark:text-green-400 uppercase">Paid</span>
                                @elseif($payout->status === 'rejected')
                                    <span class="text-[10px] font-black px-3 py-1 rounded-full bg-red-50 dark:bg-red-900/30 text-red-600 dark:text-red-400 uppercase">Rejected</span>
                                @endif
                            </td>
                            <td class="px-8 py-4 text-sm text-slate-500 dark:text-slate-400 max-w-[200px] truncate">
                                {{ Str::limit($payout->bank_details, 30) }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="px-5 lg:px-8 py-4 border-t border-slate-100 dark:border-slate-800">
                {{ $payouts->links() }}
            </div>
        @else
            <div class="flex flex-col items-center justify-center py-20 text-center px-6">
                <div class="w-16 h-16 bg-slate-100 dark:bg-slate-800 rounded-2xl flex items-center justify-center mb-4">
                    <span class="material-symbols-outlined text-3xl text-slate-300 dark:text-slate-600">account_balance_wallet</span>
                </div>
                <p class="text-sm font-bold text-slate-400 dark:text-slate-500 mb-1">No payout requests yet</p>
                <p class="text-xs text-slate-300 dark:text-slate-600">Click "Request Payout" to withdraw your balance.</p>
            </div>
        @endif
    </div>

    <!-- Request Payout Modal -->
    <flux:modal name="request-payout" class="md:w-96">
        <form wire:submit.prevent="submitRequest">
            <flux:heading size="lg" class="mb-1">Request Payout</flux:heading>
            <p class="text-sm text-slate-500 dark:text-slate-400 mb-6">Available: <span class="font-black text-indigo-600 dark:text-indigo-400">€{{ number_format($availableBalance, 2) }}</span></p>

            <div class="space-y-4">
                <flux:input wire:model="amount" type="number" step="0.01" max="{{ $availableBalance }}" label="Amount (€)" />
                <flux:textarea wire:model="bank_details" label="Bank Details (IBAN / Account Number)" placeholder="Provide your full IBAN, Bank Name, and Account Holder Name" rows="3" />

                <div class="bg-indigo-50 dark:bg-indigo-900/20 border border-indigo-100 dark:border-indigo-800/50 text-indigo-700 dark:text-indigo-300 px-4 py-3 rounded-xl text-xs">
                    <span class="material-symbols-outlined text-sm align-middle mr-1">schedule</span>
                    Payout requests may take <strong>2–5 business days</strong> after approval.
                </div>
            </div>

            <div class="flex mt-6 gap-2">
                <flux:spacer />
                <flux:modal.close>
                    <flux:button variant="ghost">Cancel</flux:button>
                </flux:modal.close>
                <flux:button type="submit" variant="primary">Submit Request</flux:button>
            </div>
        </form>
    </flux:modal>
</div>
