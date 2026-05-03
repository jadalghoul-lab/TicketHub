<div class="max-w-screen-xl mx-auto py-8 px-4" x-data="{ 
    scanner: null,
    showModal: false,
    init() {
        this.startScanner();
    },
    startScanner() {
        this.scanner = new Html5QrcodeScanner('reader', { 
            fps: 20, 
            qrbox: {width: 280, height: 280},
            rememberLastUsedCamera: true,
            aspectRatio: 1.0,
            showTorchButtonIfSupported: true
        });
        this.scanner.render((decodedText) => {
            if (this.isCooldown) return;
            this.isCooldown = true;
            $wire.scan(decodedText).then(() => {
                this.showModal = true;
                setTimeout(() => this.isCooldown = false, 1500);
            });
        });
    },
    playSuccess() {
        let audio = new Audio('https://assets.mixkit.co/active_storage/sfx/2568/2568-preview.mp3');
        audio.play();
        if (navigator.vibrate) navigator.vibrate(100);
    },
    playError() {
        let audio = new Audio('https://assets.mixkit.co/active_storage/sfx/2571/2571-preview.mp3');
        audio.play();
        if (navigator.vibrate) navigator.vibrate([100, 50, 100]);
    },
    isCooldown: false
}" @reset-result.window="setTimeout(() => { $wire.scanResult = null; showModal = false; }, 4000)"
   @scan-success.window="playSuccess()"
   @scan-error.window="playError()">
    
    <!-- Header -->
    <div class="mb-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div>
            <div class="flex items-center gap-3 mb-2">
                <span class="w-2 h-6 bg-amber-500 rounded-full"></span>
                <h1 class="text-3xl font-black text-slate-900 dark:text-white tracking-tight">Global Scanner</h1>
            </div>
            <p class="text-slate-500 dark:text-zinc-400 font-medium ml-5">Unified check-in for all your events</p>
        </div>
        
        <div class="flex items-center gap-4">
            <!-- Attendance Stats Card -->
            <div class="bg-white dark:bg-zinc-800 px-6 py-4 rounded-[2rem] border border-slate-100 dark:border-zinc-700 shadow-xl shadow-slate-100/50 dark:shadow-none flex items-center gap-6">
                <div class="relative w-12 h-12 flex items-center justify-center">
                    <svg class="w-12 h-12 -rotate-90">
                        <circle cx="24" cy="24" r="20" stroke="currentColor" stroke-width="4" fill="transparent" class="text-slate-100 dark:text-zinc-700" />
                        <circle cx="24" cy="24" r="20" stroke="currentColor" stroke-width="4" fill="transparent" 
                                class="text-amber-500" 
                                stroke-dasharray="125.6" 
                                stroke-dashoffset="{{ 125.6 * (1 - ($totalTicketsToday > 0 ? $checkedInToday / $totalTicketsToday : 0)) }}" />
                    </svg>
                    <span class="absolute text-[10px] font-black text-slate-900 dark:text-white">{{ round($totalTicketsToday > 0 ? ($checkedInToday / $totalTicketsToday) * 100 : 0) }}%</span>
                </div>
                <div>
                    <p class="text-[10px] font-black text-slate-400 dark:text-zinc-500 uppercase tracking-widest">Today's Total</p>
                    <p class="text-lg font-black text-slate-900 dark:text-white">{{ $checkedInToday }} <span class="text-slate-300 dark:text-zinc-600">/</span> {{ $totalTicketsToday }}</p>
                </div>
            </div>
            
            <a href="{{ route('dashboard') }}" class="p-4 bg-slate-100 dark:bg-zinc-800 text-slate-600 dark:text-zinc-400 rounded-2xl hover:bg-slate-200 dark:hover:bg-zinc-700 transition-all shadow-sm" wire:navigate>
                <span class="material-symbols-outlined">close</span>
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-10">
        <!-- Scanner Panel -->
        <div class="lg:col-span-5 space-y-8">
            <div class="relative group">
                <div class="absolute -inset-1 bg-gradient-to-r from-amber-500 to-orange-600 rounded-[3rem] blur opacity-25 group-hover:opacity-40 transition duration-1000 group-hover:duration-200"></div>
                <div class="relative bg-white dark:bg-zinc-900 rounded-[2.8rem] overflow-hidden border-4 border-white dark:border-zinc-800 shadow-2xl">
                    <div id="reader" class="w-full aspect-square bg-slate-950" wire:ignore></div>
                    
                    <!-- Scanner Overlay -->
                    <div class="absolute inset-0 pointer-events-none flex items-center justify-center">
                        <div class="w-64 h-64 border-2 border-white/30 rounded-3xl relative overflow-hidden">
                            <div class="absolute top-0 left-0 w-full h-1 bg-amber-500 shadow-[0_0_15px_rgba(245,158,11,0.8)] animate-scan-line"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Manual Input Card -->
            <div class="bg-white dark:bg-zinc-800 rounded-[2rem] p-8 border border-slate-100 dark:border-zinc-700 shadow-xl shadow-slate-100/50 dark:shadow-none">
                <label class="block text-[10px] font-black text-slate-400 dark:text-zinc-500 uppercase tracking-widest mb-4 ml-1">Universal Entry Check</label>
                <div class="flex gap-3">
                    <input type="text" 
                           wire:model="manualCode" 
                           placeholder="Scan any ticket code..." 
                           class="flex-grow bg-slate-50 dark:bg-zinc-900 border-none rounded-2xl p-4 font-bold text-slate-900 dark:text-white placeholder:text-slate-300 dark:placeholder:text-zinc-700 focus:ring-4 focus:ring-amber-500/20 transition-all outline-none" />
                    <button wire:click="scan()" 
                            class="bg-amber-500 text-white px-8 py-4 rounded-2xl font-black hover:bg-amber-600 transition-all shadow-lg shadow-amber-100 dark:shadow-none active:scale-95">
                        Identify
                    </button>
                </div>
            </div>
        </div>

        <!-- Results & Activity Panel -->
        <div class="lg:col-span-7 space-y-8">
            <!-- Scan Result Display -->
            <div class="min-h-[420px] relative">
                @if($scanResult)
                    <div class="animate-in fade-in zoom-in-95 duration-500 h-full">
                        @if($scanResult['success'])
                            <div class="bg-amber-500 rounded-[3rem] p-10 h-full flex flex-col justify-between text-white shadow-2xl shadow-amber-200 dark:shadow-none overflow-hidden relative group">
                                <div class="absolute -right-20 -top-20 w-64 h-64 bg-white/10 rounded-full blur-3xl"></div>
                                <div class="absolute -left-20 -bottom-20 w-64 h-64 bg-white/10 rounded-full blur-3xl"></div>

                                <div>
                                    <div class="flex items-center justify-between mb-8">
                                        <div class="w-16 h-16 bg-white/20 backdrop-blur-md rounded-2xl flex items-center justify-center">
                                            <span class="material-symbols-outlined text-4xl">travel_explore</span>
                                        </div>
                                        <div class="text-right">
                                            <p class="text-[10px] font-black uppercase tracking-widest opacity-70">Event Identified</p>
                                            <p class="text-xl font-black truncate max-w-[200px]">{{ $lastTicket->event->title }}</p>
                                        </div>
                                    </div>
                                    
                                    <h2 class="text-4xl font-black mb-2 tracking-tight">{{ $lastTicket->user?->name ?? 'Guest' }}</h2>
                                    <p class="text-white/80 font-bold">{{ $lastTicket->user?->email ?? 'No email associated' }}</p>
                                </div>

                                <div class="bg-white/10 backdrop-blur-md rounded-3xl p-6 border border-white/10 mt-8">
                                    <div class="flex justify-between items-end">
                                        <div>
                                            <p class="text-[10px] font-black uppercase tracking-widest opacity-70 mb-1">Ticket Class</p>
                                            <p class="text-lg font-black">{{ $lastTicket->ticketType->name }}</p>
                                        </div>
                                        <div class="text-right">
                                            <p class="text-[10px] font-black uppercase tracking-widest opacity-70 mb-1">Internal Reference</p>
                                            <p class="font-mono font-bold">#{{ $lastTicket->ticket_number }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="bg-red-500 rounded-[3rem] p-10 h-full flex flex-col justify-center items-center text-white shadow-2xl shadow-red-200 dark:shadow-none text-center">
                                <div class="w-24 h-24 bg-white/20 backdrop-blur-md rounded-full flex items-center justify-center mb-8 animate-bounce">
                                    <span class="material-symbols-outlined text-6xl">no_entry</span>
                                </div>
                                <h2 class="text-4xl font-black mb-4 tracking-tight uppercase">Invalid Entry</h2>
                                <p class="text-xl font-bold text-white/90 px-8">{{ $scanResult['message'] }}</p>
                            </div>
                        @endif
                    </div>
                @else
                    <div class="bg-slate-50 dark:bg-zinc-900 border-4 border-dashed border-slate-100 dark:border-zinc-800 rounded-[3rem] h-full flex flex-col items-center justify-center p-12 text-center group">
                        <div class="w-32 h-32 bg-white dark:bg-zinc-800 rounded-full flex items-center justify-center mb-8 shadow-xl shadow-slate-200/50 dark:shadow-none group-hover:scale-110 transition-transform duration-500">
                            <span class="material-symbols-outlined text-6xl text-slate-200 dark:text-zinc-700 group-hover:text-amber-500 transition-colors animate-pulse">barcode_scanner</span>
                        </div>
                        <h3 class="text-2xl font-black text-slate-900 dark:text-white mb-2">Omni-Scanner Active</h3>
                        <p class="text-slate-400 dark:text-zinc-500 font-bold max-w-xs">Scan any ticket from any of your events. The system will automatically identify the guest and event.</p>
                    </div>
                @endif
            </div>

            <!-- Recent Activity Table -->
            <div class="bg-white dark:bg-zinc-800 rounded-[2.5rem] p-8 border border-slate-100 dark:border-zinc-700 shadow-xl shadow-slate-100/50 dark:shadow-none">
                <div class="flex items-center justify-between mb-8">
                    <h3 class="text-xl font-black text-slate-900 dark:text-white flex items-center gap-2">
                        <span class="material-symbols-outlined text-amber-500">sensors</span>
                        Global Activity
                    </h3>
                </div>

                <div class="space-y-4">
                    @php
                        $organizerId = auth()->user()->organizer->id;
                    @endphp
                    @forelse(\App\Models\Ticket::whereHas('event', fn($q) => $q->where('organizer_id', $organizerId))->where('status', 'used')->orderBy('scanned_at', 'desc')->take(4)->get() as $recent)
                        <div class="flex items-center justify-between p-4 bg-slate-50 dark:bg-zinc-900 rounded-2xl border border-transparent hover:border-amber-100 dark:hover:border-amber-900 transition-all group">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 bg-white dark:bg-zinc-800 rounded-xl flex items-center justify-center text-amber-500 font-black shadow-sm group-hover:bg-amber-500 group-hover:text-white transition-all">
                                    {{ substr($recent->user?->name ?? 'G', 0, 1) }}
                                </div>
                                <div>
                                    <p class="text-sm font-black text-slate-900 dark:text-white">{{ $recent->user?->name ?? 'Guest' }}</p>
                                    <p class="text-[10px] font-bold text-slate-400 dark:text-zinc-500 truncate w-48">{{ $recent->event->title }}</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="text-[10px] font-black text-slate-400 dark:text-zinc-600 uppercase tracking-widest">{{ $recent->scanned_at->format('H:i') }}</p>
                                <p class="text-xs font-mono font-bold text-slate-500">#{{ substr($recent->ticket_number, -6) }}</p>
                            </div>
                        </div>
                    @empty
                        <div class="py-12 text-center">
                            <p class="text-slate-300 dark:text-zinc-700 font-black uppercase tracking-widest text-[10px]">No global activity recorded yet</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- Mobile Result Modal/Drawer -->
    <div x-show="showModal" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="translate-y-full opacity-0"
         x-transition:enter-end="translate-y-0 opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="translate-y-0 opacity-100"
         x-transition:leave-end="translate-y-full opacity-0"
         class="fixed inset-0 z-[100] lg:hidden flex flex-col"
         style="display: none;">
        
        <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" @click="showModal = false"></div>
        
        <div class="mt-auto relative bg-white dark:bg-zinc-900 rounded-t-[3rem] p-8 shadow-2xl flex flex-col gap-6">
            <div class="w-12 h-1.5 bg-slate-200 dark:bg-zinc-800 rounded-full mx-auto mb-2"></div>
            
            @if($scanResult)
                @if($scanResult['success'])
                    <div class="text-center">
                        <div class="w-24 h-24 bg-amber-500 rounded-full flex items-center justify-center mx-auto mb-6 shadow-xl shadow-amber-100">
                            <span class="material-symbols-outlined text-white text-5xl">check_circle</span>
                        </div>
                        <h2 class="text-3xl font-black text-slate-900 dark:text-white mb-1 uppercase tracking-tight">Verified</h2>
                        <p class="text-slate-500 font-bold mb-8">{{ $lastTicket->event->title }}</p>
                        
                        <div class="bg-slate-50 dark:bg-zinc-800 rounded-3xl p-6 text-left border border-slate-100 dark:border-zinc-700">
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Attendee</p>
                            <p class="text-xl font-black text-slate-900 dark:text-white mb-4">{{ $lastTicket->user?->name ?? 'Guest' }}</p>
                            
                            <div class="grid grid-cols-2 gap-4 pt-4 border-t border-slate-200 dark:border-zinc-700">
                                <div>
                                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Type</p>
                                    <p class="font-bold text-slate-900 dark:text-white">{{ $lastTicket->ticketType->name }}</p>
                                </div>
                                <div>
                                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Ref</p>
                                    <p class="font-bold text-slate-900 dark:text-white">#{{ substr($lastTicket->ticket_number, -6) }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="text-center">
                        <div class="w-24 h-24 bg-red-500 rounded-full flex items-center justify-center mx-auto mb-6 shadow-xl shadow-red-100">
                            <span class="material-symbols-outlined text-white text-5xl">block</span>
                        </div>
                        <h2 class="text-3xl font-black text-red-600 mb-2 uppercase tracking-tight tracking-tight">Entry Denied</h2>
                        <p class="text-slate-900 dark:text-white text-xl font-bold px-4 mb-8">{{ $scanResult['message'] }}</p>
                    </div>
                @endif
            @endif

            <button @click="showModal = false" class="w-full bg-slate-900 dark:bg-white text-white dark:text-slate-900 py-5 rounded-2xl font-black text-lg shadow-xl active:scale-95 transition-all">
                Next Scan
            </button>
        </div>
    </div>

    <!-- Scanner Custom Styles -->
    <style>
        #reader { border: none !important; }
        #reader video { border-radius: 2.5rem !important; object-fit: cover !important; }
        #reader__dashboard_section_csr button {
            background-color: #f59e0b !important;
            color: white !important;
            padding: 0.75rem 1.5rem !important;
            border-radius: 1rem !important;
            font-weight: 800 !important;
            border: none !important;
            text-transform: uppercase !important;
            font-size: 0.75rem !important;
            letter-spacing: 0.05em !important;
            margin: 1rem !important;
            cursor: pointer !important;
            width: 100%;
        }
        @keyframes scan-line {
            0% { top: 0; }
            100% { top: 100%; }
        }
        .animate-scan-line {
            animation: scan-line 2s ease-in-out infinite alternate;
        }
    </style>

    <!-- Scripts -->
    <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
</div>
