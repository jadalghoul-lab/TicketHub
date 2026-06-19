<div class="max-w-screen-2xl mx-auto w-full space-y-8">
    <!-- Header -->
    <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-6">
        <div>
            <h1 class="text-3xl font-black text-slate-900 dark:text-white tracking-tight flex items-center gap-3">
                <span class="w-2 h-8 bg-indigo-600 rounded-full"></span>
                Event Management
            </h1>
            <p class="text-slate-500 dark:text-zinc-400 font-medium ml-5">Create, edit, and manage your events and capacities.</p>
        </div>
        
        <div class="flex items-center gap-4">
            <select wire:model.live="statusFilter" class="bg-white dark:bg-zinc-800 border border-slate-100 dark:border-zinc-700 text-slate-700 dark:text-zinc-300 text-sm rounded-xl focus:ring-indigo-500 focus:border-indigo-500 block p-2.5">
                <option value="all">All Events</option>
                <option value="active">Active Events</option>
                <option value="deleted">Deleted (Archived)</option>
            </select>

            <button wire:click="createEvent" class="bg-indigo-600 text-white px-6 py-2.5 rounded-xl font-bold shadow-lg shadow-indigo-100 dark:shadow-none hover:bg-indigo-700 transition-all flex items-center gap-2 active:scale-95">
                <span class="material-symbols-outlined text-sm">add</span>
                New Event
            </button>
        </div>
    </div>

    @if (session()->has('success'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)" class="bg-green-50 dark:bg-green-900/30 border border-green-200 dark:border-green-800 text-green-700 dark:text-green-400 px-4 py-3 rounded-xl flex items-center gap-3">
            <span class="material-symbols-outlined">check_circle</span>
            <span class="font-bold text-sm">{{ session('success') }}</span>
        </div>
    @endif

    <!-- Events Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($events as $event)
            <div class="bg-white dark:bg-zinc-800 rounded-[2rem] border border-slate-50 dark:border-zinc-700 shadow-xl shadow-slate-100/50 dark:shadow-none overflow-hidden flex flex-col group relative {{ $event->trashed() ? 'opacity-75 grayscale' : '' }}">
                
                <!-- Status Badge -->
                <div class="absolute top-4 right-4 z-10 flex gap-2">
                    @if($event->trashed())
                        <span class="bg-red-500 text-white text-[10px] font-black px-3 py-1 rounded-full uppercase shadow-lg">Deleted</span>
                    @else
                        @if($event->status === \App\Enums\EventStatus::PUBLISHED)
                            <span class="bg-green-500 text-white text-[10px] font-black px-3 py-1 rounded-full uppercase shadow-lg cursor-pointer hover:bg-green-600 transition-colors" wire:click="togglePublish({{ $event->id }})">Published</span>
                        @else
                            <span class="bg-amber-500 text-white text-[10px] font-black px-3 py-1 rounded-full uppercase shadow-lg cursor-pointer hover:bg-amber-600 transition-colors" wire:click="togglePublish({{ $event->id }})">Draft</span>
                        @endif
                    @endif
                </div>

                <div class="h-48 bg-slate-100 dark:bg-zinc-700 relative overflow-hidden">
                    @if($event->image)
                        <img src="{{ $event->image_url }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" alt="{{ $event->title }}">
                    @else
                        <div class="w-full h-full flex items-center justify-center text-slate-300 dark:text-zinc-600">
                            <span class="material-symbols-outlined text-5xl">image</span>
                        </div>
                    @endif
                </div>
                
                <div class="p-6 flex-grow flex flex-col">
                    <div class="flex justify-between items-start mb-2">
                        <h3 class="font-black text-lg text-slate-900 dark:text-white line-clamp-1" title="{{ $event->title }}">{{ $event->title }}</h3>
                    </div>
                    
                    <div class="space-y-2 mt-4 flex-grow">
                        <div class="flex items-center gap-2 text-sm text-slate-500 dark:text-zinc-400">
                            <span class="material-symbols-outlined text-[16px]">calendar_month</span>
                            {{ $event->start_date->format('M d, Y') }} @if($event->time) • {{ $event->time->format('H:i') }} @endif
                        </div>
                        <div class="flex items-center gap-2 text-sm text-slate-500 dark:text-zinc-400">
                            <span class="material-symbols-outlined text-[16px]">location_on</span>
                            {{ $event->city }}, {{ $event->country }}
                        </div>
                        <div class="flex items-center gap-2 text-sm text-slate-500 dark:text-zinc-400">
                            <span class="material-symbols-outlined text-[16px]">group</span>
                            Capacity: <span class="font-bold text-slate-900 dark:text-white">{{ number_format($event->capacity) }}</span>
                        </div>
                    </div>

                    <div class="pt-6 mt-6 border-t border-slate-50 dark:border-zinc-700 flex items-center justify-between">
                        @if($event->trashed())
                            <button wire:click="restoreEvent({{ $event->id }})" class="text-indigo-600 dark:text-indigo-400 font-bold text-sm hover:underline flex items-center gap-1">
                                <span class="material-symbols-outlined text-[18px]">restore</span> Restore
                            </button>
                        @else
                            <a href="{{ route('organizer.events.tickets', $event->slug) }}" wire:navigate class="text-emerald-600 dark:text-emerald-400 font-bold text-sm hover:underline flex items-center gap-1">
                                <span class="material-symbols-outlined text-[18px]">confirmation_number</span> Tickets
                            </a>
                            <button wire:click="editEvent({{ $event->id }})" class="text-indigo-600 dark:text-indigo-400 font-bold text-sm hover:underline flex items-center gap-1">
                                <span class="material-symbols-outlined text-[18px]">edit</span> Edit
                            </button>
                            <button wire:click="deleteEvent({{ $event->id }})" class="text-red-600 dark:text-red-400 font-bold text-sm hover:underline flex items-center gap-1" onclick="confirm('Are you sure you want to delete this event?') || event.stopImmediatePropagation()">
                                <span class="material-symbols-outlined text-[18px]">delete</span>
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full py-20 flex flex-col items-center justify-center text-center bg-white dark:bg-zinc-800 rounded-[2.5rem] border border-slate-50 dark:border-zinc-700 shadow-sm">
                <span class="material-symbols-outlined text-6xl text-slate-200 dark:text-zinc-700 mb-4">event_busy</span>
                <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-2">No events found</h3>
                <p class="text-slate-500 dark:text-zinc-400 max-w-sm mb-6">You haven't created any events matching this filter yet.</p>
                <button wire:click="createEvent" class="bg-indigo-600 text-white px-6 py-2.5 rounded-xl font-bold shadow-lg shadow-indigo-100 dark:shadow-none hover:bg-indigo-700 transition-all flex items-center gap-2 active:scale-95">
                    <span class="material-symbols-outlined text-sm">add</span>
                    Create First Event
                </button>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="mt-8">
        {{ $events->links() }}
    </div>

    <!-- Create/Edit Modal -->
    @if($showModal)
        <div class="fixed inset-0 bg-slate-900/60 dark:bg-black/80 backdrop-blur-sm z-50 flex items-center justify-center p-4 overflow-y-auto">
            <div class="bg-white dark:bg-zinc-900 rounded-[2.5rem] w-full max-w-2xl shadow-2xl animate-in fade-in zoom-in duration-300 my-8">
                <div class="p-8 border-b border-slate-100 dark:border-zinc-800 flex justify-between items-center sticky top-0 bg-white dark:bg-zinc-900 rounded-t-[2.5rem] z-10">
                    <h3 class="text-2xl font-black text-slate-900 dark:text-white">{{ $isEditing ? 'Edit Event' : 'Create New Event' }}</h3>
                    <button wire:click="$set('showModal', false)" class="text-slate-400 hover:text-slate-600 dark:hover:text-white bg-slate-50 dark:bg-zinc-800 w-10 h-10 rounded-full flex items-center justify-center transition-colors">
                        <span class="material-symbols-outlined">close</span>
                    </button>
                </div>

                <form wire:submit.prevent="saveEvent" class="p-8 space-y-6">
                    <!-- Title & Category -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="md:col-span-2">
                            <label class="block text-[10px] font-black text-slate-400 dark:text-zinc-500 uppercase tracking-widest mb-2 ml-1">Event Title</label>
                            <input type="text" wire:model="title" class="w-full bg-slate-50 dark:bg-zinc-800 border border-slate-100 dark:border-zinc-700 rounded-2xl p-4 text-slate-900 dark:text-white font-bold focus:ring-2 focus:ring-indigo-500 outline-none transition-all" placeholder="E.g., Summer Jazz Festival">
                            @error('title') <span class="text-red-500 text-xs font-bold mt-1 ml-1">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-[10px] font-black text-slate-400 dark:text-zinc-500 uppercase tracking-widest mb-2 ml-1">Category</label>
                            <select wire:model="category" class="w-full bg-slate-50 dark:bg-zinc-800 border border-slate-100 dark:border-zinc-700 rounded-2xl p-4 text-slate-900 dark:text-white font-bold focus:ring-2 focus:ring-indigo-500 outline-none transition-all appearance-none">
                                <option value="Music">Music</option>
                                <option value="Tech">Tech</option>
                                <option value="Sports">Sports</option>
                                <option value="Arts">Arts</option>
                                <option value="Business">Business</option>
                            </select>
                            @error('category') <span class="text-red-500 text-xs font-bold mt-1 ml-1">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <!-- Date & Time & Capacity -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <label class="block text-[10px] font-black text-slate-400 dark:text-zinc-500 uppercase tracking-widest mb-2 ml-1">Start Date</label>
                            <input type="date" wire:model="start_date" class="w-full bg-slate-50 dark:bg-zinc-800 border border-slate-100 dark:border-zinc-700 rounded-2xl p-4 text-slate-900 dark:text-white font-bold focus:ring-2 focus:ring-indigo-500 outline-none transition-all">
                            @error('start_date') <span class="text-red-500 text-xs font-bold mt-1 ml-1">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-[10px] font-black text-slate-400 dark:text-zinc-500 uppercase tracking-widest mb-2 ml-1">Time</label>
                            <input type="time" wire:model="time" class="w-full bg-slate-50 dark:bg-zinc-800 border border-slate-100 dark:border-zinc-700 rounded-2xl p-4 text-slate-900 dark:text-white font-bold focus:ring-2 focus:ring-indigo-500 outline-none transition-all">
                            @error('time') <span class="text-red-500 text-xs font-bold mt-1 ml-1">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-[10px] font-black text-slate-400 dark:text-zinc-500 uppercase tracking-widest mb-2 ml-1">Capacity (Max Attendees)</label>
                            <input type="number" wire:model="capacity" min="1" class="w-full bg-slate-50 dark:bg-zinc-800 border border-slate-100 dark:border-zinc-700 rounded-2xl p-4 text-slate-900 dark:text-white font-bold focus:ring-2 focus:ring-indigo-500 outline-none transition-all" placeholder="500">
                            @error('capacity') <span class="text-red-500 text-xs font-bold mt-1 ml-1">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <!-- Location -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-[10px] font-black text-slate-400 dark:text-zinc-500 uppercase tracking-widest mb-2 ml-1">City</label>
                            <input type="text" wire:model="city" class="w-full bg-slate-50 dark:bg-zinc-800 border border-slate-100 dark:border-zinc-700 rounded-2xl p-4 text-slate-900 dark:text-white font-bold focus:ring-2 focus:ring-indigo-500 outline-none transition-all" placeholder="Berlin">
                            @error('city') <span class="text-red-500 text-xs font-bold mt-1 ml-1">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-[10px] font-black text-slate-400 dark:text-zinc-500 uppercase tracking-widest mb-2 ml-1">Country</label>
                            <input type="text" wire:model="country" class="w-full bg-slate-50 dark:bg-zinc-800 border border-slate-100 dark:border-zinc-700 rounded-2xl p-4 text-slate-900 dark:text-white font-bold focus:ring-2 focus:ring-indigo-500 outline-none transition-all" placeholder="Germany">
                            @error('country') <span class="text-red-500 text-xs font-bold mt-1 ml-1">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <!-- Description -->
                    <div>
                        <label class="block text-[10px] font-black text-slate-400 dark:text-zinc-500 uppercase tracking-widest mb-2 ml-1">Description</label>
                        <textarea wire:model="description" rows="4" class="w-full bg-slate-50 dark:bg-zinc-800 border border-slate-100 dark:border-zinc-700 rounded-2xl p-4 text-slate-900 dark:text-white font-medium focus:ring-2 focus:ring-indigo-500 outline-none transition-all" placeholder="Describe what attendees can expect..."></textarea>
                        @error('description') <span class="text-red-500 text-xs font-bold mt-1 ml-1">{{ $message }}</span> @enderror
                    </div>

                    <!-- Image -->
                    <div>
                        <label class="block text-[10px] font-black text-slate-400 dark:text-zinc-500 uppercase tracking-widest mb-2 ml-1">Cover Image</label>
                        <input type="file" wire:model="image" class="block w-full text-sm text-slate-500 file:mr-4 file:py-3 file:px-6 file:rounded-xl file:border-0 file:text-sm file:font-bold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 transition-all dark:file:bg-indigo-900/30 dark:file:text-indigo-400 dark:hover:file:bg-indigo-900/50">
                        <div wire:loading wire:target="image" class="text-xs text-indigo-600 mt-2 font-bold animate-pulse">Uploading image...</div>
                        @error('image') <span class="text-red-500 text-xs font-bold mt-1 ml-1">{{ $message }}</span> @enderror
                        
                        @if ($image && !is_string($image))
                            <div class="mt-4 rounded-2xl overflow-hidden h-32 w-full max-w-sm">
                                <img src="{{ $image->temporaryUrl() }}" class="object-cover w-full h-full">
                            </div>
                        @endif
                    </div>

                    <!-- Actions -->
                    <div class="flex gap-4 pt-6 border-t border-slate-100 dark:border-zinc-800 sticky bottom-0 bg-white dark:bg-zinc-900 pb-2">
                        <button type="button" wire:click="$set('showModal', false)" class="flex-1 px-6 py-4 rounded-2xl font-bold text-slate-500 dark:text-zinc-400 hover:bg-slate-50 dark:hover:bg-zinc-800 transition-all">Cancel</button>
                        <button type="submit" class="flex-1 bg-indigo-600 text-white px-6 py-4 rounded-2xl font-bold shadow-lg shadow-indigo-100 dark:shadow-none hover:bg-indigo-700 transition-all active:scale-95">
                            {{ $isEditing ? 'Save Changes' : 'Create Event' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>
