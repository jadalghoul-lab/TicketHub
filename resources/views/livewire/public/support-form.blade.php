<div class="bg-white dark:bg-slate-900/60 border border-slate-100 dark:border-slate-800/80 rounded-3xl p-8 shadow-sm dark:shadow-[0_0_50px_rgba(99,102,241,0.05)] relative overflow-hidden transition-all duration-300">
    <div class="flex items-center gap-3 mb-8">
        <span class="material-symbols-outlined text-indigo-600 dark:text-indigo-400 text-2xl transform rotate-[-30deg]">near_me</span>
        <h2 class="font-serif text-2xl font-semibold text-slate-900 dark:text-white">Send a Message</h2>
    </div>

    <!-- Success Message Animation -->
    @if($success)
        <div x-data="{ show: true }" x-show="show" x-transition.opacity.duration.500ms class="absolute inset-0 bg-white/95 dark:bg-slate-900/95 backdrop-blur-sm z-10 flex flex-col items-center justify-center text-center p-8">
            <div class="w-16 h-16 bg-green-100 dark:bg-green-900/30 rounded-full flex items-center justify-center text-green-600 dark:text-green-400 mb-4 animate-bounce">
                <span class="material-symbols-outlined text-3xl">check_circle</span>
            </div>
            <h3 class="text-2xl font-bold text-slate-900 dark:text-white mb-2">Message Sent!</h3>
            <p class="text-slate-500 dark:text-slate-400 mb-6">Thank you for reaching out. Our support team will get back to you shortly.</p>
            <button wire:click="$set('success', false)" class="text-indigo-600 dark:text-indigo-400 font-bold hover:text-indigo-700 dark:hover:text-indigo-300 transition-colors">
                Send another message
            </button>
        </div>
    @endif

    <form wire:submit.prevent="submit" class="space-y-6 relative z-0">
        @error('general')
            <div class="bg-red-50 text-red-700 p-4 rounded-xl mb-6 flex items-start gap-3">
                <span class="material-symbols-outlined">error</span>
                <p class="font-medium">{{ $message }}</p>
            </div>
        @enderror

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="group">
                <label class="text-[10px] uppercase font-bold tracking-wider text-slate-400 dark:text-slate-500 mb-2 ml-1 block transition-colors group-focus-within:text-indigo-600 dark:group-focus-within:text-indigo-400">Your Name</label>
                <div class="relative">
                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 material-symbols-outlined text-xl transition-colors group-focus-within:text-indigo-600 dark:group-focus-within:text-indigo-400">person</span>
                    <input type="text" wire:model.blur="name" required class="w-full bg-slate-50 dark:bg-[#050814] border border-slate-200 dark:border-slate-800/60 rounded-xl pl-12 pr-4 py-3.5 text-slate-900 dark:text-white focus:bg-white dark:focus:bg-slate-900/60 focus:border-indigo-600 dark:focus:border-indigo-500 focus:ring-4 focus:ring-indigo-100 dark:focus:ring-indigo-900/20 transition-all outline-none @error('name') !border-red-500 !focus:ring-red-100 @enderror">
                </div>
                @error('name') <span class="text-red-500 text-sm mt-2 block animate-pulse">{{ $message }}</span> @enderror
            </div>
            <div class="group">
                <label class="text-[10px] uppercase font-bold tracking-wider text-slate-400 dark:text-slate-500 mb-2 ml-1 block transition-colors group-focus-within:text-indigo-600 dark:group-focus-within:text-indigo-400">Email Address</label>
                <div class="relative">
                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 material-symbols-outlined text-xl transition-colors group-focus-within:text-indigo-600 dark:group-focus-within:text-indigo-400">mail</span>
                    <input type="email" wire:model.blur="email" required class="w-full bg-slate-50 dark:bg-[#050814] border border-slate-200 dark:border-slate-800/60 rounded-xl pl-12 pr-4 py-3.5 text-slate-900 dark:text-white focus:bg-white dark:focus:bg-slate-900/60 focus:border-indigo-600 dark:focus:border-indigo-500 focus:ring-4 focus:ring-indigo-100 dark:focus:ring-indigo-900/20 transition-all outline-none @error('email') !border-red-500 !focus:ring-red-100 @enderror">
                </div>
                @error('email') <span class="text-red-500 text-sm mt-2 block animate-pulse">{{ $message }}</span> @enderror
            </div>
        </div>

        <div class="group">
            <label class="text-[10px] uppercase font-bold tracking-wider text-slate-400 dark:text-slate-500 mb-2 ml-1 block transition-colors group-focus-within:text-indigo-600 dark:group-focus-within:text-indigo-400">Subject</label>
            <div class="relative">
                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 material-symbols-outlined text-xl transition-colors group-focus-within:text-indigo-600 dark:group-focus-within:text-indigo-400">topic</span>
                <select wire:model.blur="subject" required class="w-full bg-slate-50 dark:bg-[#050814] border border-slate-200 dark:border-slate-800/60 rounded-xl pl-12 pr-4 py-3.5 text-slate-900 dark:text-white focus:bg-white dark:focus:bg-slate-900/60 focus:border-indigo-600 dark:focus:border-indigo-500 focus:ring-4 focus:ring-indigo-100 dark:focus:ring-indigo-900/20 transition-all outline-none appearance-none @error('subject') !border-red-500 !focus:ring-red-100 @enderror">
                    <option value="">Select a topic...</option>
                    <option value="Ticket Issue">Problem with a ticket</option>
                    <option value="Event Question">Question about an event</option>
                    <option value="Refund Request">Refund request</option>
                    <option value="Account">Account & billing</option>
                    <option value="Other">Other</option>
                </select>
                <span class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 material-symbols-outlined pointer-events-none">expand_more</span>
            </div>
            @error('subject') <span class="text-red-500 text-sm mt-2 block animate-pulse">{{ $message }}</span> @enderror
        </div>

        <div class="group">
            <label class="text-[10px] uppercase font-bold tracking-wider text-slate-400 dark:text-slate-500 mb-2 ml-1 block transition-colors group-focus-within:text-indigo-600 dark:group-focus-within:text-indigo-400">Message</label>
            <div class="relative">
                <span class="absolute left-4 top-4 text-slate-400 material-symbols-outlined text-xl transition-colors group-focus-within:text-indigo-600 dark:group-focus-within:text-indigo-400">chat</span>
                <textarea wire:model.blur="message" rows="5" required class="w-full bg-slate-50 dark:bg-[#050814] border border-slate-200 dark:border-slate-800/60 rounded-xl pl-12 pr-4 py-3.5 text-slate-900 dark:text-white focus:bg-white dark:focus:bg-slate-900/60 focus:border-indigo-600 dark:focus:border-indigo-500 focus:ring-4 focus:ring-indigo-100 dark:focus:ring-indigo-900/20 transition-all outline-none resize-none @error('message') !border-red-500 !focus:ring-red-100 @enderror"></textarea>
            </div>
            @error('message') <span class="text-red-500 text-sm mt-2 block animate-pulse">{{ $message }}</span> @enderror
        </div>

        <button type="submit" wire:loading.attr="disabled" class="w-full bg-indigo-600 dark:bg-indigo-500 text-white font-bold py-4 rounded-xl shadow-lg shadow-indigo-600/30 dark:shadow-[0_0_20px_rgba(99,102,241,0.2)] hover:bg-indigo-700 dark:hover:bg-indigo-600 active:scale-[0.98] transition-all flex items-center justify-center gap-2 group disabled:opacity-70 disabled:cursor-not-allowed">
            <span wire:loading.remove wire:target="submit" class="material-symbols-outlined text-xl group-hover:translate-x-1 transition-transform">send</span>
            <span wire:loading.remove wire:target="submit">Send Message</span>
            
            <svg wire:loading wire:target="submit" class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            <span wire:loading wire:target="submit">Sending...</span>
        </button>
    </form>
</div>
