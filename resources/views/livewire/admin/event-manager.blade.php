<div class="max-w-screen-2xl mx-auto w-full space-y-8" x-data="{ editing: false }">
    <!-- Header -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h1 class="text-3xl font-black text-slate-900 dark:text-white tracking-tight flex items-center gap-3">
                <span class="w-2 h-8 bg-indigo-500 rounded-full"></span>
                Global Event Management
            </h1>
            <p class="text-slate-500 dark:text-slate-400 font-medium ml-5">Oversee, moderate, and manage all platform events</p>
        </div>
    </div>

    <!-- Filters & Search -->
    <div class="flex flex-col md:flex-row gap-4 items-center justify-between bg-white dark:bg-slate-900 p-4 rounded-[2rem] border border-slate-50 dark:border-slate-800 shadow-sm">
        <div class="relative w-full md:w-96">
            <span class="absolute left-4 top-1/2 -translate-y-1/2 material-symbols-outlined text-slate-400">search</span>
            <input type="text" wire:model.live="search" placeholder="Search events or organizers..." class="w-full border-none rounded-xl py-3 pl-12 pr-4 text-sm focus:ring-2 focus:ring-indigo-500 transition-colors duration-150 dark:text-white" style="background-color: var(--input-bg) !important;">
        </div>
        <div class="flex gap-2">
            <button wire:click="$set('statusFilter', 'all')" class="px-4 py-2 rounded-xl text-xs font-bold {{ $statusFilter === 'all' ? 'bg-indigo-600 text-white shadow-md' : 'bg-slate-50 dark:bg-slate-800 text-slate-500 dark:text-slate-400' }} transition-all">All</button>
            <button wire:click="$set('statusFilter', 'published')" class="px-4 py-2 rounded-xl text-xs font-bold {{ $statusFilter === 'published' ? 'bg-indigo-600 text-white shadow-md' : 'bg-slate-50 dark:bg-slate-800 text-slate-500 dark:text-slate-400' }} transition-all">Published</button>
            <button wire:click="$set('statusFilter', 'blocked')" class="px-4 py-2 rounded-xl text-xs font-bold {{ $statusFilter === 'blocked' ? 'bg-indigo-600 text-white shadow-md' : 'bg-slate-50 dark:bg-slate-800 text-slate-500 dark:text-slate-400' }} transition-all">Blocked</button>
            <button wire:click="$set('statusFilter', 'deleted')" class="px-4 py-2 rounded-xl text-xs font-bold {{ $statusFilter === 'deleted' ? 'bg-indigo-600 text-white shadow-md' : 'bg-slate-50 dark:bg-slate-800 text-slate-500 dark:text-slate-400' }} transition-all">Deleted</button>
        </div>
    </div>

    @if (session()->has('success'))
        <div class="bg-emerald-500 text-white p-4 rounded-2xl font-bold flex items-center gap-3">
            <span class="material-symbols-outlined">check_circle</span>
            {{ session('success') }}
        </div>
    @endif

    <!-- Events Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        @forelse($events as $event)
            <div class="bg-white dark:bg-slate-900 rounded-[2rem] border border-slate-50 dark:border-slate-800 shadow-xl shadow-slate-100/50 dark:shadow-none overflow-hidden flex flex-col group relative {{ $event->trashed() ? 'opacity-75 grayscale' : '' }}">
                
                <!-- Admin Status Badge -->
                <div class="absolute top-4 right-4 z-10 flex gap-2">
                    @if($event->trashed())
                        <span class="bg-red-500 text-white text-[10px] font-black px-3 py-1 rounded-full uppercase shadow-lg">Deleted</span>
                    @elseif($event->status === \App\Enums\EventStatus::BLOCKED)
                        <span class="bg-slate-900 text-white text-[10px] font-black px-3 py-1 rounded-full uppercase shadow-lg">Blocked</span>
                    @else
                        <span class="bg-indigo-500 text-white text-[10px] font-black px-3 py-1 rounded-full uppercase shadow-lg">{{ $event->status->value }}</span>
                    @endif
                </div>

                <div class="h-40 bg-slate-100 dark:bg-slate-800 relative overflow-hidden">
                    @if($event->image)
                        <img src="{{ Storage::url($event->image) }}" class="w-full h-full object-cover" alt="{{ $event->title }}">
                    @else
                        <div class="w-full h-full flex items-center justify-center text-slate-300 dark:text-slate-700">
                            <span class="material-symbols-outlined text-4xl">image</span>
                        </div>
                    @endif
                    <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
                    <div class="absolute bottom-4 left-4 text-white">
                        <p class="text-[10px] font-black uppercase tracking-widest opacity-80">{{ $event->category }}</p>
                        <h3 class="font-bold text-sm truncate w-48">{{ $event->title }}</h3>
                    </div>
                </div>
                
                <div class="p-6 flex-grow flex flex-col">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-8 h-8 bg-slate-50 dark:bg-slate-800 rounded-lg flex items-center justify-center text-[10px] font-black text-indigo-600">
                            {{ substr($event->organizer->company_name, 0, 1) }}
                        </div>
                        <div>
                            <p class="text-[10px] text-slate-400 uppercase font-black tracking-tighter">Organizer</p>
                            <p class="text-xs font-bold text-slate-700 dark:text-slate-300">{{ $event->organizer->company_name }}</p>
                        </div>
                    </div>
                    
                    <div class="space-y-2 flex-grow">
                        <div class="flex items-center gap-2 text-[10px] font-bold text-slate-500 dark:text-slate-500">
                            <span class="material-symbols-outlined text-[14px]">calendar_month</span>
                            {{ $event->start_date->format('M d, Y') }}
                        </div>
                    </div>

                    <div class="pt-6 mt-6 border-t border-slate-50 dark:border-slate-800 flex items-center justify-between gap-2">
                        @if($event->trashed())
                            <button wire:click="restoreEvent({{ $event->id }})" class="flex-1 bg-emerald-50 dark:bg-emerald-900/20 text-emerald-600 dark:text-emerald-400 py-2 rounded-xl text-[10px] font-black uppercase hover:bg-emerald-100 transition-all">Restore</button>
                        @else
                            <button wire:click="toggleBlock({{ $event->id }})" class="p-2 {{ $event->status === \App\Enums\EventStatus::BLOCKED ? 'text-emerald-600' : 'text-amber-600' }} hover:bg-slate-50 rounded-lg transition-all" title="{{ $event->status === \App\Enums\EventStatus::BLOCKED ? 'Unblock' : 'Block' }}">
                                <span class="material-symbols-outlined text-sm">{{ $event->status === \App\Enums\EventStatus::BLOCKED ? 'check_circle' : 'block' }}</span>
                            </button>
                            <button wire:click="editEvent({{ $event->id }})" class="p-2 text-blue-600 hover:bg-slate-50 rounded-lg transition-all" title="Edit">
                                <span class="material-symbols-outlined text-sm">edit</span>
                            </button>
                            <button wire:click="deleteEvent({{ $event->id }})" class="p-2 text-red-600 hover:bg-slate-50 rounded-lg transition-all" onclick="confirm('Delete this event?') || event.stopImmediatePropagation()" title="Delete">
                                <span class="material-symbols-outlined text-sm">delete</span>
                            </button>
                        @endif
                        <a href="{{ route('public.events.show', $event->slug) }}" target="_blank" class="p-2 text-slate-400 hover:text-indigo-600 transition-all">
                            <span class="material-symbols-outlined text-sm">visibility</span>
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full py-20 text-center bg-white dark:bg-slate-900 rounded-[2.5rem] border border-slate-50 dark:border-slate-800">
                <p class="text-slate-400 font-bold">No events found matching your filters.</p>
            </div>
        @endforelse
    </div>

    <div class="mt-8">
        {{ $events->links() }}
    </div>

    <!-- Edit Modal -->
    @if($showModal)
        <div class="fixed inset-0 bg-slate-900/60 dark:bg-black/80 backdrop-blur-sm z-50 flex items-center justify-center p-4 overflow-y-auto">
            <div class="bg-white dark:bg-slate-900 rounded-[2.5rem] w-full max-w-2xl shadow-2xl animate-in fade-in zoom-in duration-300 my-8">
                <div class="p-8 border-b border-slate-100 dark:border-slate-800 flex justify-between items-center sticky top-0 bg-white dark:bg-slate-900 rounded-t-[2.5rem] z-10">
                    <h3 class="text-2xl font-black text-slate-900 dark:text-white">Admin Edit: {{ $title }}</h3>
                    <button wire:click="$set('showModal', false)" class="text-slate-400 hover:text-slate-600 dark:hover:text-white bg-slate-50 dark:bg-slate-800 w-10 h-10 rounded-full flex items-center justify-center transition-colors">
                        <span class="material-symbols-outlined">close</span>
                    </button>
                </div>

                <form wire:submit.prevent="saveEvent" class="p-8 space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="md:col-span-2">
                            <label class="block text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-widest mb-2 ml-1">Event Title</label>
                            <input type="text" wire:model="title" class="w-full bg-slate-50 dark:bg-slate-800 border border-slate-100 dark:border-slate-700 rounded-2xl p-4 text-slate-900 dark:text-white font-bold focus:ring-2 focus:ring-indigo-500 outline-none transition-all">
                        </div>
                        <div>
                            <label class="block text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-widest mb-2 ml-1">Category</label>
                            <select wire:model="category" class="w-full bg-slate-50 dark:bg-slate-800 border border-slate-100 dark:border-slate-700 rounded-2xl p-4 text-slate-900 dark:text-white font-bold focus:ring-2 focus:ring-indigo-500 outline-none transition-all">
                                <option value="Music">Music</option>
                                <option value="Tech">Tech</option>
                                <option value="Sports">Sports</option>
                                <option value="Arts">Arts</option>
                                <option value="Business">Business</option>
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <label class="block text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-widest mb-2 ml-1">Start Date</label>
                            <input type="date" wire:model="start_date" class="w-full bg-slate-50 dark:bg-slate-800 border border-slate-100 dark:border-slate-700 rounded-2xl p-4 text-slate-900 dark:text-white font-bold focus:ring-2 focus:ring-indigo-500 outline-none transition-all">
                        </div>
                        <div>
                            <label class="block text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-widest mb-2 ml-1">Time</label>
                            <input type="time" wire:model="time" class="w-full bg-slate-50 dark:bg-slate-800 border border-slate-100 dark:border-slate-700 rounded-2xl p-4 text-slate-900 dark:text-white font-bold focus:ring-2 focus:ring-indigo-500 outline-none transition-all">
                        </div>
                        <div>
                            <label class="block text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-widest mb-2 ml-1">Capacity</label>
                            <input type="number" wire:model="capacity" class="w-full bg-slate-50 dark:bg-slate-800 border border-slate-100 dark:border-slate-700 rounded-2xl p-4 text-slate-900 dark:text-white font-bold focus:ring-2 focus:ring-indigo-500 outline-none transition-all">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-widest mb-2 ml-1">City</label>
                            <input type="text" wire:model="city" class="w-full bg-slate-50 dark:bg-slate-800 border border-slate-100 dark:border-slate-700 rounded-2xl p-4 text-slate-900 dark:text-white font-bold focus:ring-2 focus:ring-indigo-500 outline-none transition-all">
                        </div>
                        <div>
                            <label class="block text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-widest mb-2 ml-1">Country</label>
                            <input type="text" wire:model="country" class="w-full bg-slate-50 dark:bg-slate-800 border border-slate-100 dark:border-slate-700 rounded-2xl p-4 text-slate-900 dark:text-white font-bold focus:ring-2 focus:ring-indigo-500 outline-none transition-all">
                        </div>
                    </div>

                    <div>
                        <label class="block text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-widest mb-2 ml-1">Description</label>
                        <textarea wire:model="description" rows="3" class="w-full bg-slate-50 dark:bg-slate-800 border border-slate-100 dark:border-slate-700 rounded-2xl p-4 text-slate-900 dark:text-white font-medium focus:ring-2 focus:ring-indigo-500 outline-none transition-all"></textarea>
                    </div>

                    <div>
                        <label class="block text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-widest mb-2 ml-1">Update Image (Optional)</label>
                        <input type="file" wire:model="image" class="block w-full text-sm text-slate-500 file:mr-4 file:py-3 file:px-6 file:rounded-xl file:border-0 file:text-sm file:font-bold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 transition-all dark:file:bg-indigo-900/30 dark:file:text-indigo-400">
                    </div>

                    <div class="flex gap-4 pt-6 border-t border-slate-100 dark:border-slate-800">
                        <button type="button" wire:click="$set('showModal', false)" class="flex-1 px-6 py-4 rounded-2xl font-bold text-slate-500 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800 transition-all">Cancel</button>
                        <button type="submit" class="flex-1 bg-indigo-600 text-white px-6 py-4 rounded-2xl font-bold shadow-lg shadow-indigo-100 dark:shadow-none hover:bg-indigo-700 transition-all active:scale-95">
                            Update Event as Admin
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>
