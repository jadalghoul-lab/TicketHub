<div class="max-w-screen-2xl mx-auto w-full space-y-8">
    <!-- Header -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h1 class="text-3xl font-black text-slate-900 dark:text-white tracking-tight flex items-center gap-3">
                <span class="w-2 h-8 bg-indigo-500 rounded-full"></span>
                User & Organizer Management
            </h1>
            <p class="text-slate-500 dark:text-slate-400 font-medium ml-5">Manage roles, partners, and platform access</p>
        </div>
        <button wire:click="createOrganizer" class="bg-indigo-600 text-white px-8 py-3 rounded-2xl font-bold shadow-lg shadow-indigo-100 dark:shadow-none hover:bg-indigo-700 transition-all active:scale-95 flex items-center gap-2">
            <span class="material-symbols-outlined">person_add</span>
            Add New Organizer
        </button>
    </div>

    <!-- Filters & Search -->
    <div class="flex flex-col md:flex-row gap-4 items-center justify-between bg-white dark:bg-slate-900 p-4 rounded-[2rem] border border-slate-50 dark:border-slate-800 shadow-sm">
        <div class="relative w-full md:w-96">
            <span class="absolute left-4 top-1/2 -translate-y-1/2 material-symbols-outlined text-slate-400">search</span>
            <input type="text" wire:model.live="search" placeholder="Search by name, email or company..." class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-xl py-3 pl-12 pr-4 text-sm focus:ring-2 focus:ring-indigo-500 transition-colors duration-150 dark:text-white">
        </div>
        <div class="flex gap-2">
            <button wire:click="$set('filterStatus', 'all')" class="px-4 py-2 rounded-xl text-xs font-bold {{ $filterStatus === 'all' ? 'bg-indigo-600 text-white shadow-md' : 'bg-slate-50 dark:bg-slate-800 text-slate-500 dark:text-slate-400' }} transition-all">All</button>
            <button wire:click="$set('filterStatus', 'active')" class="px-4 py-2 rounded-xl text-xs font-bold {{ $filterStatus === 'active' ? 'bg-indigo-600 text-white shadow-md' : 'bg-slate-50 dark:bg-slate-800 text-slate-500 dark:text-slate-400' }} transition-all">Active</button>
            <button wire:click="$set('filterStatus', 'deleted')" class="px-4 py-2 rounded-xl text-xs font-bold {{ $filterStatus === 'deleted' ? 'bg-indigo-600 text-white shadow-md' : 'bg-slate-50 dark:bg-slate-800 text-slate-500 dark:text-slate-400' }} transition-all">Deleted</button>
        </div>
    </div>

    @if (session()->has('success'))
        <div class="bg-emerald-500 text-white p-4 rounded-2xl font-bold flex items-center gap-3 animate-in fade-in slide-in-from-top-4">
            <span class="material-symbols-outlined">check_circle</span>
            {{ session('success') }}
        </div>
    @endif

    <!-- Table -->
    <div class="bg-white dark:bg-slate-900 rounded-[2.5rem] border border-slate-50 dark:border-slate-800 shadow-xl overflow-hidden">
        <table class="w-full text-left">
            <thead>
                <tr class="text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-[0.2em] border-b border-slate-50 dark:border-slate-800">
                    <th class="px-8 py-6">User / Company</th>
                    <th class="px-8 py-6">Role</th>
                    <th class="px-8 py-6">Status</th>
                    <th class="px-8 py-6">Joined</th>
                    <th class="px-8 py-6 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50 dark:divide-slate-800">
                @forelse($organizers as $organizer)
                <tr class="group hover:bg-slate-50/50 dark:hover:bg-slate-800/50 transition-all {{ $organizer->trashed() ? 'opacity-60 grayscale' : '' }}">
                    <td class="px-8 py-6">
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 bg-slate-100 dark:bg-slate-800 rounded-xl flex items-center justify-center font-bold text-indigo-600">
                                {{ substr($organizer->company_name, 0, 1) }}
                            </div>
                            <div>
                                <h4 class="font-bold text-slate-900 dark:text-white">{{ $organizer->company_name }}</h4>
                                <p class="text-xs text-slate-500 dark:text-slate-400">{{ $organizer->user->name }} • {{ $organizer->user->email }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-8 py-6">
                        <select wire:change="changeRole({{ $organizer->user_id }}, $event.target.value)" class="bg-slate-50 dark:bg-slate-800 border-none rounded-lg text-[10px] font-black uppercase py-1 px-3 focus:ring-2 focus:ring-indigo-500 cursor-pointer dark:text-white">
                            <option value="customer" {{ $organizer->user->role->value === 'customer' ? 'selected' : '' }}>Customer</option>
                            <option value="organizer" {{ $organizer->user->role->value === 'organizer' ? 'selected' : '' }}>Organizer</option>
                            <option value="admin" {{ $organizer->user->role->value === 'admin' ? 'selected' : '' }}>Admin</option>
                        </select>
                    </td>
                    <td class="px-8 py-6 text-sm">
                        @if($organizer->trashed())
                            <span class="bg-red-100 text-red-600 dark:bg-red-900/30 dark:text-red-400 px-3 py-1 rounded-full text-[10px] font-black uppercase">Deleted</span>
                        @else
                            <span class="bg-emerald-100 text-emerald-600 dark:bg-emerald-900/30 dark:text-emerald-400 px-3 py-1 rounded-full text-[10px] font-black uppercase">{{ $organizer->status }}</span>
                        @endif
                    </td>
                    <td class="px-8 py-6 text-sm text-slate-500 dark:text-slate-400 font-medium">
                        {{ $organizer->created_at->format('M d, Y') }}
                    </td>
                    <td class="px-8 py-6 text-right">
                        <div class="flex justify-end gap-2">
                            @if($organizer->trashed())
                                <button wire:click="restoreOrganizer({{ $organizer->id }})" class="p-2 text-indigo-600 hover:bg-indigo-50 rounded-lg transition-all" title="Restore">
                                    <span class="material-symbols-outlined">restore</span>
                                </button>
                            @else
                                <button wire:click="editOrganizer({{ $organizer->id }})" class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition-all" title="Edit">
                                    <span class="material-symbols-outlined">edit</span>
                                </button>
                                <button wire:click="deleteOrganizer({{ $organizer->id }})" class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-all" onclick="confirm('Are you sure you want to delete this organizer?') || event.stopImmediatePropagation()" title="Delete">
                                    <span class="material-symbols-outlined">delete</span>
                                </button>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="px-8 py-20 text-center text-slate-400">No organizers found matching your criteria.</td></tr>
                @endforelse
            </tbody>
        </table>
        <div class="p-8 border-t border-slate-50 dark:border-slate-800">
            {{ $organizers->links() }}
        </div>
    </div>

    <!-- Modal -->
    @if($showModal)
    <div class="fixed inset-0 bg-slate-900/60 dark:bg-black/80 backdrop-blur-sm z-50 flex items-center justify-center p-4">
        <div class="bg-white dark:bg-slate-900 rounded-[2.5rem] w-full max-w-xl shadow-2xl animate-in fade-in zoom-in duration-300">
            <div class="p-8 border-b border-slate-100 dark:border-slate-800 flex justify-between items-center">
                <h3 class="text-2xl font-black text-slate-900 dark:text-white">{{ $isEditing ? 'Edit Organizer' : 'Add New Organizer' }}</h3>
                <button wire:click="$set('showModal', false)" class="text-slate-400 hover:text-slate-600 bg-slate-50 dark:bg-slate-800 w-10 h-10 rounded-full flex items-center justify-center">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>

            <form wire:submit.prevent="saveOrganizer" class="p-8 space-y-6">
                <div class="grid grid-cols-2 gap-6">
                    <div class="col-span-2">
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">User Full Name</label>
                        <input type="text" wire:model="name" class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-2xl p-4 font-bold dark:text-white focus:ring-2 focus:ring-indigo-500">
                        @error('name') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Email Address</label>
                        <input type="email" wire:model="email" class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-2xl p-4 font-bold dark:text-white focus:ring-2 focus:ring-indigo-500">
                        @error('email') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Password {{ $isEditing ? '(Optional)' : '' }}</label>
                        <input type="password" wire:model="password" class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-2xl p-4 font-bold dark:text-white focus:ring-2 focus:ring-indigo-500">
                        @error('password') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>
                    <div class="col-span-2">
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Company / Organization Name</label>
                        <input type="text" wire:model="company_name" class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-2xl p-4 font-bold dark:text-white focus:ring-2 focus:ring-indigo-500">
                        @error('company_name') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Role</label>
                        <select wire:model="role" class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-2xl p-4 font-bold dark:text-white focus:ring-2 focus:ring-indigo-500 appearance-none">
                            <option value="customer">Customer</option>
                            <option value="organizer">Organizer</option>
                            <option value="admin">Admin</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Status</label>
                        <select wire:model="status" class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-2xl p-4 font-bold dark:text-white focus:ring-2 focus:ring-indigo-500 appearance-none">
                            <option value="active">Active</option>
                            <option value="pending">Pending</option>
                            <option value="suspended">Suspended</option>
                        </select>
                    </div>
                </div>

                <div class="flex gap-4 pt-6 border-t border-slate-100 dark:border-slate-800">
                    <button type="button" wire:click="$set('showModal', false)" class="flex-1 px-6 py-4 rounded-2xl font-bold text-slate-500 hover:bg-slate-50 transition-all">Cancel</button>
                    <button type="submit" class="flex-1 bg-indigo-600 text-white px-6 py-4 rounded-2xl font-bold shadow-lg shadow-indigo-100 dark:shadow-none hover:bg-indigo-700 transition-all active:scale-95">
                        {{ $isEditing ? 'Update Organizer' : 'Create Account' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
    @endif
</div>
