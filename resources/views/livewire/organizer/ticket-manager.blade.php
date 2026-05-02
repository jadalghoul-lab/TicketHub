<div class="max-w-screen-2xl mx-auto w-full space-y-8">
    <!-- Header -->
    <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-6">
        <div>
            <div class="flex items-center gap-2 mb-2">
                <a href="{{ route('organizer.events.index') }}" wire:navigate class="text-slate-400 dark:text-zinc-500 hover:text-indigo-600 dark:hover:text-indigo-400 font-bold text-sm flex items-center gap-1 transition-colors">
                    <span class="material-symbols-outlined text-[18px]">arrow_back</span>
                    Back to Events
                </a>
            </div>
            <h1 class="text-3xl font-black text-slate-900 dark:text-white tracking-tight flex items-center gap-3">
                <span class="w-2 h-8 bg-emerald-500 rounded-full"></span>
                Ticket Types
            </h1>
            <p class="text-slate-500 dark:text-zinc-400 font-medium ml-5">Manage pricing, quantities, and sales dates for <span class="text-slate-900 dark:text-white font-bold">{{ $event->title }}</span></p>
        </div>
        
        <div class="flex items-center gap-4">
            <button wire:click="createTicket" class="bg-emerald-500 text-white px-6 py-2.5 rounded-xl font-bold shadow-lg shadow-emerald-100 dark:shadow-none hover:bg-emerald-600 transition-all flex items-center gap-2 active:scale-95">
                <span class="material-symbols-outlined text-sm">add</span>
                New Ticket Type
            </button>
        </div>
    </div>

    @if (session()->has('success'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)" class="bg-green-50 dark:bg-green-900/30 border border-green-200 dark:border-green-800 text-green-700 dark:text-green-400 px-4 py-3 rounded-xl flex items-center gap-3">
            <span class="material-symbols-outlined">check_circle</span>
            <span class="font-bold text-sm">{{ session('success') }}</span>
        </div>
    @endif

    <!-- Event Summary Card -->
    <div class="bg-white dark:bg-zinc-800 rounded-[2rem] border border-slate-50 dark:border-zinc-700 p-6 flex flex-col md:flex-row gap-6 items-center shadow-sm">
        <div class="w-full md:w-32 h-32 rounded-2xl bg-slate-100 dark:bg-zinc-700 overflow-hidden flex-shrink-0">
            @if($event->image)
                <img src="{{ Storage::url($event->image) }}" class="w-full h-full object-cover">
            @else
                <div class="w-full h-full flex items-center justify-center text-slate-300 dark:text-zinc-600">
                    <span class="material-symbols-outlined text-4xl">image</span>
                </div>
            @endif
        </div>
        <div class="flex-grow">
            <h2 class="text-xl font-black text-slate-900 dark:text-white mb-2">{{ $event->title }}</h2>
            <div class="flex flex-wrap gap-4 text-sm font-bold text-slate-500 dark:text-zinc-400">
                <div class="flex items-center gap-1">
                    <span class="material-symbols-outlined text-[18px]">calendar_month</span>
                    {{ $event->start_date->format('M d, Y') }}
                </div>
                <div class="flex items-center gap-1">
                    <span class="material-symbols-outlined text-[18px]">group</span>
                    Total Capacity: {{ number_format($event->capacity) }}
                </div>
                <div class="flex items-center gap-1">
                    <span class="material-symbols-outlined text-[18px]">confirmation_number</span>
                    Tickets Created: <span class="text-emerald-600 dark:text-emerald-400 ml-1">{{ number_format($ticketTypes->sum('quantity')) }}</span>
                </div>
            </div>
        </div>
        <div>
            @php
                $remaining = $event->capacity - $ticketTypes->sum('quantity');
            @endphp
            <div class="text-center p-4 bg-slate-50 dark:bg-zinc-900 rounded-2xl border {{ $remaining < 0 ? 'border-red-200 dark:border-red-900/50' : 'border-slate-100 dark:border-zinc-800' }}">
                <p class="text-[10px] font-black text-slate-400 dark:text-zinc-500 uppercase tracking-widest mb-1">Unallocated Capacity</p>
                <p class="text-2xl font-black {{ $remaining < 0 ? 'text-red-500' : 'text-slate-900 dark:text-white' }}">{{ number_format($remaining) }}</p>
            </div>
        </div>
    </div>

    <!-- Tickets Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($ticketTypes as $ticket)
            <div class="bg-white dark:bg-zinc-800 rounded-[2rem] border border-slate-50 dark:border-zinc-700 shadow-xl shadow-slate-100/50 dark:shadow-none overflow-hidden relative group transition-all hover:-translate-y-1">
                <div class="absolute top-0 left-0 w-full h-2 bg-emerald-500"></div>
                <div class="p-6">
                    <div class="flex justify-between items-start mb-4">
                        <h3 class="font-black text-xl text-slate-900 dark:text-white">{{ $ticket->name }}</h3>
                        <div class="bg-emerald-50 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400 font-black px-3 py-1 rounded-xl text-lg">
                            ${{ number_format($ticket->price, 2) }}
                        </div>
                    </div>
                    
                    @if($ticket->description)
                        <p class="text-sm text-slate-500 dark:text-zinc-400 mb-6 line-clamp-2 h-10">{{ $ticket->description }}</p>
                    @else
                        <div class="h-10 mb-6"></div>
                    @endif
                    
                    <div class="space-y-3 bg-slate-50 dark:bg-zinc-900 rounded-2xl p-4 mb-6">
                        <div class="flex justify-between text-sm">
                            <span class="text-slate-500 dark:text-zinc-400 font-bold">Quantity</span>
                            <span class="font-black text-slate-900 dark:text-white">{{ number_format($ticket->quantity) }}</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-slate-500 dark:text-zinc-400 font-bold">Max per order</span>
                            <span class="font-black text-slate-900 dark:text-white">{{ $ticket->max_per_order }}</span>
                        </div>
                        
                        @if($ticket->sales_start || $ticket->sales_end)
                        <div class="pt-3 mt-3 border-t border-slate-200 dark:border-zinc-700/50">
                            @if($ticket->sales_start)
                            <div class="flex items-center gap-2 text-xs font-bold text-slate-500 dark:text-zinc-400">
                                <span class="material-symbols-outlined text-[14px]">play_circle</span>
                                Starts: {{ $ticket->sales_start->format('M d, g:i A') }}
                            </div>
                            @endif
                            @if($ticket->sales_end)
                            <div class="flex items-center gap-2 text-xs font-bold text-slate-500 dark:text-zinc-400 mt-1">
                                <span class="material-symbols-outlined text-[14px]">stop_circle</span>
                                Ends: {{ $ticket->sales_end->format('M d, g:i A') }}
                            </div>
                            @endif
                        </div>
                        @endif
                    </div>

                    <div class="flex items-center justify-between">
                        <button wire:click="editTicket({{ $ticket->id }})" class="text-indigo-600 dark:text-indigo-400 font-bold text-sm hover:underline flex items-center gap-1">
                            <span class="material-symbols-outlined text-[18px]">edit</span> Edit
                        </button>
                        <button wire:click="deleteTicket({{ $ticket->id }})" class="text-red-600 dark:text-red-400 font-bold text-sm hover:underline flex items-center gap-1" onclick="confirm('Are you sure you want to delete this ticket type?') || event.stopImmediatePropagation()">
                            <span class="material-symbols-outlined text-[18px]">delete</span> Delete
                        </button>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full py-16 flex flex-col items-center justify-center text-center bg-white dark:bg-zinc-800 rounded-[2.5rem] border border-slate-50 dark:border-zinc-700 shadow-sm">
                <span class="material-symbols-outlined text-6xl text-slate-200 dark:text-zinc-700 mb-4">local_activity</span>
                <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-2">No Ticket Types Found</h3>
                <p class="text-slate-500 dark:text-zinc-400 max-w-sm mb-6">Create ticket types like "General Admission" or "VIP" to start selling.</p>
                <button wire:click="createTicket" class="bg-emerald-500 text-white px-6 py-2.5 rounded-xl font-bold shadow-lg shadow-emerald-100 dark:shadow-none hover:bg-emerald-600 transition-all flex items-center gap-2 active:scale-95">
                    <span class="material-symbols-outlined text-sm">add</span>
                    Create Ticket Type
                </button>
            </div>
        @endforelse
    </div>

    <!-- Create/Edit Modal -->
    @if($showModal)
        <div class="fixed inset-0 bg-slate-900/60 dark:bg-black/80 backdrop-blur-sm z-50 flex items-center justify-center p-4 overflow-y-auto">
            <div class="bg-white dark:bg-zinc-900 rounded-[2.5rem] w-full max-w-2xl shadow-2xl animate-in fade-in zoom-in duration-300 my-8">
                <div class="p-8 border-b border-slate-100 dark:border-zinc-800 flex justify-between items-center sticky top-0 bg-white dark:bg-zinc-900 rounded-t-[2.5rem] z-10">
                    <h3 class="text-2xl font-black text-slate-900 dark:text-white">{{ $isEditing ? 'Edit Ticket Type' : 'Create Ticket Type' }}</h3>
                    <button wire:click="$set('showModal', false)" class="text-slate-400 hover:text-slate-600 dark:hover:text-white bg-slate-50 dark:bg-zinc-800 w-10 h-10 rounded-full flex items-center justify-center transition-colors">
                        <span class="material-symbols-outlined">close</span>
                    </button>
                </div>

                <form wire:submit.prevent="saveTicket" class="p-8 space-y-6">
                    <!-- Name & Price -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-[10px] font-black text-slate-400 dark:text-zinc-500 uppercase tracking-widest mb-2 ml-1">Ticket Name</label>
                            <input type="text" wire:model="name" class="w-full bg-slate-50 dark:bg-zinc-800 border border-slate-100 dark:border-zinc-700 rounded-2xl p-4 text-slate-900 dark:text-white font-bold focus:ring-2 focus:ring-emerald-500 outline-none transition-all" placeholder="e.g., VIP Pass">
                            @error('name') <span class="text-red-500 text-xs font-bold mt-1 ml-1">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-[10px] font-black text-slate-400 dark:text-zinc-500 uppercase tracking-widest mb-2 ml-1">Price ($)</label>
                            <input type="number" step="0.01" wire:model="price" class="w-full bg-slate-50 dark:bg-zinc-800 border border-slate-100 dark:border-zinc-700 rounded-2xl p-4 text-slate-900 dark:text-white font-bold focus:ring-2 focus:ring-emerald-500 outline-none transition-all" placeholder="0.00 (Free if 0)">
                            @error('price') <span class="text-red-500 text-xs font-bold mt-1 ml-1">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <!-- Quantity & Max Per Order -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-[10px] font-black text-slate-400 dark:text-zinc-500 uppercase tracking-widest mb-2 ml-1">Quantity Available</label>
                            <input type="number" wire:model="quantity" min="1" class="w-full bg-slate-50 dark:bg-zinc-800 border border-slate-100 dark:border-zinc-700 rounded-2xl p-4 text-slate-900 dark:text-white font-bold focus:ring-2 focus:ring-emerald-500 outline-none transition-all" placeholder="100">
                            @error('quantity') <span class="text-red-500 text-xs font-bold mt-1 ml-1">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-[10px] font-black text-slate-400 dark:text-zinc-500 uppercase tracking-widest mb-2 ml-1">Max Per Order</label>
                            <input type="number" wire:model="max_per_order" min="1" class="w-full bg-slate-50 dark:bg-zinc-800 border border-slate-100 dark:border-zinc-700 rounded-2xl p-4 text-slate-900 dark:text-white font-bold focus:ring-2 focus:ring-emerald-500 outline-none transition-all" placeholder="5">
                            @error('max_per_order') <span class="text-red-500 text-xs font-bold mt-1 ml-1">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <!-- Sales Dates -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-[10px] font-black text-slate-400 dark:text-zinc-500 uppercase tracking-widest mb-2 ml-1">Sales Start (Optional)</label>
                            <input type="datetime-local" wire:model="sales_start" class="w-full bg-slate-50 dark:bg-zinc-800 border border-slate-100 dark:border-zinc-700 rounded-2xl p-4 text-slate-900 dark:text-white font-bold focus:ring-2 focus:ring-emerald-500 outline-none transition-all">
                            @error('sales_start') <span class="text-red-500 text-xs font-bold mt-1 ml-1">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-[10px] font-black text-slate-400 dark:text-zinc-500 uppercase tracking-widest mb-2 ml-1">Sales End (Optional)</label>
                            <input type="datetime-local" wire:model="sales_end" class="w-full bg-slate-50 dark:bg-zinc-800 border border-slate-100 dark:border-zinc-700 rounded-2xl p-4 text-slate-900 dark:text-white font-bold focus:ring-2 focus:ring-emerald-500 outline-none transition-all">
                            @error('sales_end') <span class="text-red-500 text-xs font-bold mt-1 ml-1">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <!-- Description -->
                    <div>
                        <label class="block text-[10px] font-black text-slate-400 dark:text-zinc-500 uppercase tracking-widest mb-2 ml-1">Description (Optional)</label>
                        <textarea wire:model="description" rows="3" class="w-full bg-slate-50 dark:bg-zinc-800 border border-slate-100 dark:border-zinc-700 rounded-2xl p-4 text-slate-900 dark:text-white font-medium focus:ring-2 focus:ring-emerald-500 outline-none transition-all" placeholder="E.g., Includes front row seating and a backstage pass."></textarea>
                        @error('description') <span class="text-red-500 text-xs font-bold mt-1 ml-1">{{ $message }}</span> @enderror
                    </div>

                    <!-- Actions -->
                    <div class="flex gap-4 pt-6 border-t border-slate-100 dark:border-zinc-800 sticky bottom-0 bg-white dark:bg-zinc-900 pb-2">
                        <button type="button" wire:click="$set('showModal', false)" class="flex-1 px-6 py-4 rounded-2xl font-bold text-slate-500 dark:text-zinc-400 hover:bg-slate-50 dark:hover:bg-zinc-800 transition-all">Cancel</button>
                        <button type="submit" class="flex-1 bg-emerald-500 text-white px-6 py-4 rounded-2xl font-bold shadow-lg shadow-emerald-100 dark:shadow-none hover:bg-emerald-600 transition-all active:scale-95">
                            {{ $isEditing ? 'Save Changes' : 'Create Ticket' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>
