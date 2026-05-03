<div class="py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-7xl mx-auto">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 md:mb-12 gap-6">
            <div>
                <h1 class="text-3xl md:text-4xl font-black text-slate-900 dark:text-white tracking-tight">Coupons & Discounts</h1>
                <p class="text-slate-500 dark:text-zinc-500 font-medium mt-2">Boost your sales with promotional codes.</p>
            </div>
            <button wire:click="createCoupon" 
                    class="w-full md:w-auto bg-indigo-600 hover:bg-indigo-700 text-white px-8 py-4 rounded-2xl font-bold transition-all active:scale-95 shadow-xl shadow-indigo-100 dark:shadow-none flex items-center justify-center gap-2">
                <span class="material-symbols-outlined">add</span>
                New Coupon
            </button>
        </div>

        @if (session()->has('success'))
            <div class="mb-8 p-4 bg-green-50 dark:bg-green-900/30 border border-green-100 dark:border-green-800 rounded-2xl text-green-700 dark:text-green-400 font-bold flex items-center gap-3">
                <span class="material-symbols-outlined">check_circle</span>
                {{ session('success') }}
            </div>
        @endif

        <!-- Coupons List -->
        <div class="bg-white dark:bg-zinc-900 rounded-[2.5rem] border border-slate-100 dark:border-zinc-800 shadow-2xl shadow-slate-100/50 dark:shadow-none overflow-hidden">
            <!-- Desktop Table -->
            <div class="hidden md:block">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50 dark:bg-zinc-800/50">
                            <th class="px-8 py-6 text-[10px] font-black text-slate-400 dark:text-zinc-500 uppercase tracking-widest">Code</th>
                            <th class="px-8 py-6 text-[10px] font-black text-slate-400 dark:text-zinc-500 uppercase tracking-widest">Discount</th>
                            <th class="px-8 py-6 text-[10px] font-black text-slate-400 dark:text-zinc-500 uppercase tracking-widest">Restrictions</th>
                            <th class="px-8 py-6 text-[10px] font-black text-slate-400 dark:text-zinc-500 uppercase tracking-widest">Usage</th>
                            <th class="px-8 py-6 text-[10px] font-black text-slate-400 dark:text-zinc-500 uppercase tracking-widest">Expiry</th>
                            <th class="px-8 py-6 text-[10px] font-black text-slate-400 dark:text-zinc-500 uppercase tracking-widest text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50 dark:divide-zinc-800">
                        @forelse($coupons as $coupon)
                            <tr class="hover:bg-slate-50/50 dark:hover:bg-zinc-800/30 transition-colors group">
                                <td class="px-8 py-6">
                                    <span class="bg-indigo-50 dark:bg-indigo-900/30 text-indigo-700 dark:text-indigo-400 px-3 py-1.5 rounded-lg font-mono font-black text-sm uppercase tracking-wider border border-indigo-100 dark:border-indigo-800">
                                        {{ $coupon->code }}
                                    </span>
                                </td>
                                <td class="px-8 py-6">
                                    <span class="text-lg font-black text-slate-900 dark:text-white">
                                        {{ $coupon->type === 'percentage' ? $coupon->value . '%' : '€' . $coupon->value }}
                                    </span>
                                </td>
                                <td class="px-8 py-6">
                                    @if($coupon->event)
                                        <div class="flex items-center gap-2 text-slate-600 dark:text-zinc-400 text-xs font-bold">
                                            <span class="material-symbols-outlined text-sm">event</span>
                                            {{ $coupon->event->title }}
                                        </div>
                                    @else
                                        <span class="text-slate-400 dark:text-zinc-600 text-xs font-bold uppercase tracking-widest">Global</span>
                                    @endif
                                </td>
                                <td class="px-8 py-6">
                                    <div class="flex items-center gap-3">
                                        <div class="flex-1 h-2 w-24 bg-slate-100 dark:bg-zinc-800 rounded-full overflow-hidden">
                                            @php 
                                                $percent = $coupon->max_usages ? ($coupon->usages_count / $coupon->max_usages) * 100 : 0;
                                                $color = $percent > 90 ? 'bg-red-500' : ($percent > 50 ? 'bg-orange-500' : 'bg-green-500');
                                            @endphp
                                            <div class="h-full {{ $color }} transition-all" style="width: {{ $percent }}%"></div>
                                        </div>
                                        <span class="text-xs font-black text-slate-900 dark:text-white">
                                            {{ $coupon->usages_count }} / {{ $coupon->max_usages ?? '∞' }}
                                        </span>
                                    </div>
                                </td>
                                <td class="px-8 py-6">
                                    @if($coupon->expires_at)
                                        <div class="text-xs font-bold {{ $coupon->expires_at->isPast() ? 'text-red-500' : 'text-slate-600 dark:text-zinc-400' }}">
                                            {{ $coupon->expires_at->format('M d, Y') }}
                                        </div>
                                    @else
                                        <span class="text-slate-400 dark:text-zinc-600 text-xs font-bold">No Expiry</span>
                                    @endif
                                </td>
                                <td class="px-8 py-6 text-right">
                                    <div class="flex justify-end gap-2">
                                        <button wire:click="editCoupon({{ $coupon->id }})" class="p-2 text-slate-400 hover:text-indigo-600 dark:hover:text-indigo-400 hover:bg-indigo-50 rounded-xl transition-all">
                                            <span class="material-symbols-outlined">edit</span>
                                        </button>
                                        <button wire:confirm="Are you sure?" wire:click="deleteCoupon({{ $coupon->id }})" class="p-2 text-slate-400 hover:text-red-600 hover:bg-red-50 rounded-xl transition-all">
                                            <span class="material-symbols-outlined">delete</span>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="px-8 py-24 text-center text-slate-400">No coupons yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Mobile Cards -->
            <div class="md:hidden divide-y divide-slate-100 dark:divide-zinc-800">
                @forelse($coupons as $coupon)
                    <div class="p-6 space-y-4">
                        <div class="flex justify-between items-start">
                            <span class="bg-indigo-50 dark:bg-indigo-900/30 text-indigo-700 dark:text-indigo-400 px-3 py-1.5 rounded-lg font-mono font-black text-sm uppercase tracking-wider border border-indigo-100 dark:border-indigo-800">
                                {{ $coupon->code }}
                            </span>
                            <div class="flex gap-2">
                                <button wire:click="editCoupon({{ $coupon->id }})" class="p-2 text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-xl">
                                    <span class="material-symbols-outlined">edit</span>
                                </button>
                                <button wire:confirm="Are you sure?" wire:click="deleteCoupon({{ $coupon->id }})" class="p-2 text-slate-400 hover:text-red-600 hover:bg-red-50 rounded-xl">
                                    <span class="material-symbols-outlined">delete</span>
                                </button>
                            </div>
                        </div>
                        
                        <div class="flex justify-between items-center">
                            <div>
                                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Discount</p>
                                <p class="text-xl font-black text-slate-900 dark:text-white">
                                    {{ $coupon->type === 'percentage' ? $coupon->value . '%' : '€' . $coupon->value }}
                                </p>
                            </div>
                            <div class="text-right">
                                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Usage</p>
                                <p class="text-sm font-black text-slate-900 dark:text-white">
                                    {{ $coupon->usages_count }} <span class="text-slate-300">/</span> {{ $coupon->max_usages ?? '∞' }}
                                </p>
                            </div>
                        </div>

                        <div class="flex items-center gap-2 text-slate-600 dark:text-zinc-400 text-xs font-bold">
                            @if($coupon->event)
                                <span class="material-symbols-outlined text-sm">event</span>
                                {{ $coupon->event->title }}
                            @else
                                <span class="material-symbols-outlined text-sm">public</span>
                                Global Coupon
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="p-12 text-center text-slate-400 font-bold uppercase tracking-widest text-xs">No coupons yet.</div>
                @endforelse
            </div>
            <div class="px-8 py-6 bg-slate-50 dark:bg-zinc-800/30 border-t border-slate-100 dark:border-zinc-800">
                {{ $coupons->links() }}
            </div>
        </div>

        <!-- Modal -->
        @if($showModal)
            <div class="fixed inset-0 z-[200] overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
                <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                    <!-- Simple backdrop without excessive blur -->
                    <div class="fixed inset-0 bg-slate-900/80 transition-opacity" aria-hidden="true" wire:click="$set('showModal', false)"></div>

                    <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                    <!-- Modal content with explicit relative z-index -->
                    <div class="relative z-10 inline-block align-bottom bg-white dark:bg-zinc-900 rounded-[2.5rem] text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-xl sm:w-full border border-slate-100 dark:border-zinc-800">
                        <div class="p-10">
                            <h3 class="text-3xl font-black text-slate-900 dark:text-white tracking-tight mb-8" id="modal-title">
                                {{ $isEditing ? 'Edit Coupon' : 'New Discount Coupon' }}
                            </h3>

                            <form wire:submit.prevent="saveCoupon" class="space-y-6">
                                <!-- Code -->
                                <div>
                                    <label class="block text-[10px] font-black text-slate-400 dark:text-zinc-500 uppercase tracking-widest mb-2 ml-1">Coupon Code</label>
                                    <input type="text" wire:model="code" 
                                           placeholder="e.g. SUMMER25"
                                           class="w-full px-6 py-4 rounded-2xl bg-slate-50 dark:bg-zinc-800 border-none focus:ring-4 focus:ring-indigo-500/20 dark:text-white font-bold transition-all shadow-inner uppercase">
                                    @error('code') <span class="text-red-500 text-[10px] font-black uppercase mt-2 ml-1">{{ $message }}</span> @enderror
                                </div>

                                <div class="grid grid-cols-2 gap-6">
                                    <!-- Type -->
                                    <div>
                                        <label class="block text-[10px] font-black text-slate-400 dark:text-zinc-500 uppercase tracking-widest mb-2 ml-1">Discount Type</label>
                                        <select wire:model.live="type" 
                                                class="w-full px-6 py-4 rounded-2xl bg-slate-50 dark:bg-zinc-800 border-none focus:ring-4 focus:ring-indigo-500/20 dark:text-white font-bold transition-all shadow-inner">
                                            <option value="percentage">Percentage (%)</option>
                                            <option value="fixed">Fixed Amount (€)</option>
                                        </select>
                                        @error('type') <span class="text-red-500 text-[10px] font-black uppercase mt-2 ml-1">{{ $message }}</span> @enderror
                                    </div>

                                    <!-- Value -->
                                    <div>
                                        <label class="block text-[10px] font-black text-slate-400 dark:text-zinc-500 uppercase tracking-widest mb-2 ml-1">Value</label>
                                        <div class="relative">
                                            <input type="number" step="0.01" wire:model="value" 
                                                   class="w-full px-6 py-4 rounded-2xl bg-slate-50 dark:bg-zinc-800 border-none focus:ring-4 focus:ring-indigo-500/20 dark:text-white font-bold transition-all shadow-inner">
                                            <span class="absolute right-6 top-1/2 -translate-y-1/2 text-slate-400 font-black">
                                                {{ $type === 'percentage' ? '%' : '€' }}
                                            </span>
                                        </div>
                                        @error('value') <span class="text-red-500 text-[10px] font-black uppercase mt-2 ml-1">{{ $message }}</span> @enderror
                                    </div>
                                </div>

                                <!-- Event Restriction -->
                                <div>
                                    <label class="block text-[10px] font-black text-slate-400 dark:text-zinc-500 uppercase tracking-widest mb-2 ml-1">Restrict to Event (Optional)</label>
                                    <select wire:model="event_id" 
                                            class="w-full px-6 py-4 rounded-2xl bg-slate-50 dark:bg-zinc-800 border-none focus:ring-4 focus:ring-indigo-500/20 dark:text-white font-bold transition-all shadow-inner">
                                        <option value="">All Events (Global Coupon)</option>
                                        @foreach($events as $event)
                                            <option value="{{ $event->id }}">{{ $event->title }}</option>
                                        @endforeach
                                    </select>
                                    @error('event_id') <span class="text-red-500 text-[10px] font-black uppercase mt-2 ml-1">{{ $message }}</span> @enderror
                                </div>

                                <div class="grid grid-cols-2 gap-6">
                                    <!-- Max Usages -->
                                    <div>
                                        <label class="block text-[10px] font-black text-slate-400 dark:text-zinc-500 uppercase tracking-widest mb-2 ml-1">Max Usages</label>
                                        <input type="number" wire:model="max_usages" placeholder="Unlimited"
                                               class="w-full px-6 py-4 rounded-2xl bg-slate-50 dark:bg-zinc-800 border-none focus:ring-4 focus:ring-indigo-500/20 dark:text-white font-bold transition-all shadow-inner">
                                        @error('max_usages') <span class="text-red-500 text-[10px] font-black uppercase mt-2 ml-1">{{ $message }}</span> @enderror
                                    </div>

                                    <!-- Expiry -->
                                    <div>
                                        <label class="block text-[10px] font-black text-slate-400 dark:text-zinc-500 uppercase tracking-widest mb-2 ml-1">Expiry Date</label>
                                        <input type="date" wire:model="expires_at" 
                                               class="w-full px-6 py-4 rounded-2xl bg-slate-50 dark:bg-zinc-800 border-none focus:ring-4 focus:ring-indigo-500/20 dark:text-white font-bold transition-all shadow-inner">
                                        @error('expires_at') <span class="text-red-500 text-[10px] font-black uppercase mt-2 ml-1">{{ $message }}</span> @enderror
                                    </div>
                                </div>

                                <!-- One per customer toggle -->
                                <div class="flex items-center justify-between p-6 bg-slate-50 dark:bg-zinc-800 rounded-2xl">
                                    <div>
                                        <h4 class="text-sm font-black text-slate-900 dark:text-white">Once per customer</h4>
                                        <p class="text-[10px] text-slate-500 dark:text-zinc-500 font-bold uppercase tracking-widest">Restrict each user to one use</p>
                                    </div>
                                    <button type="button" wire:click="$toggle('once_per_customer')" 
                                            class="w-14 h-8 rounded-full transition-all relative {{ $once_per_customer ? 'bg-indigo-600' : 'bg-slate-200 dark:bg-zinc-700' }}">
                                        <div class="absolute top-1 left-1 w-6 h-6 bg-white rounded-full shadow-md transition-transform {{ $once_per_customer ? 'translate-x-6' : '' }}"></div>
                                    </button>
                                </div>

                                <div class="pt-8 flex gap-4">
                                    <button type="button" wire:click="$set('showModal', false)" 
                                            class="flex-1 px-8 py-4 rounded-2xl font-bold text-slate-500 hover:bg-slate-100 dark:hover:bg-zinc-800 transition-all">
                                        Cancel
                                    </button>
                                    <button type="submit" 
                                            class="flex-1 bg-indigo-600 hover:bg-indigo-700 text-white px-8 py-4 rounded-2xl font-bold transition-all active:scale-95 shadow-xl shadow-indigo-100 dark:shadow-none">
                                        {{ $isEditing ? 'Update Coupon' : 'Create Coupon' }}
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
