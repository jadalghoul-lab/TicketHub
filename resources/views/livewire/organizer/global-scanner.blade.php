<div class="min-h-screen bg-slate-50 dark:bg-slate-950 transition-colors duration-200" x-data="{
    scanner: null,
    showModal: false,
    cameras: [],
    currentCameraIndex: 0,
    isStarting: false,
    isCooldown: false,

    async init() {
        await this.loadCameras();
        this.startCamera();
    },

    async loadCameras() {
        try {
            const devices = await Html5Qrcode.getCameras();
            this.cameras = devices;
            // Default to back camera (environment) on mobile
            const backIdx = devices.findIndex(d =>
                d.label.toLowerCase().includes('back') ||
                d.label.toLowerCase().includes('rear') ||
                d.label.toLowerCase().includes('environment')
            );
            if (backIdx !== -1) this.currentCameraIndex = backIdx;
        } catch(e) {
            console.warn('Camera list error:', e);
        }
    },

    startCamera() {
        if (this.isStarting) return;
        this.isStarting = true;

        if (this.scanner) {
            this.scanner.stop().catch(() => {});
            this.scanner = null;
        }

        this.scanner = new Html5Qrcode('reader');
        const cameraId = this.cameras.length > 0
            ? this.cameras[this.currentCameraIndex].id
            : { facingMode: 'environment' };

        this.scanner.start(
            cameraId,
            { fps: 20, qrbox: { width: 250, height: 250 }, aspectRatio: 1.0 },
            (decodedText) => {
                if (this.isCooldown) return;
                this.isCooldown = true;
                $wire.scan(decodedText).then(() => {
                    this.showModal = true;
                    setTimeout(() => this.isCooldown = false, 1500);
                });
            },
            () => {}
        ).then(() => {
            this.isStarting = false;
        }).catch(err => {
            console.error('Camera start error:', err);
            this.isStarting = false;
        });
    },

    flipCamera() {
        if (this.cameras.length < 2) return;
        this.currentCameraIndex = (this.currentCameraIndex + 1) % this.cameras.length;
        this.startCamera();
    },

    playSuccess() {
        let audio = new Audio('https://assets.mixkit.co/active_storage/sfx/2568/2568-preview.mp3');
        audio.play().catch(() => {});
        if (navigator.vibrate) navigator.vibrate(100);
    },
    playError() {
        let audio = new Audio('https://assets.mixkit.co/active_storage/sfx/2571/2571-preview.mp3');
        audio.play().catch(() => {});
        if (navigator.vibrate) navigator.vibrate([100, 50, 100]);
    }
}" @reset-result.window="setTimeout(() => { $wire.scanResult = null; showModal = false; }, 4000)"
   @scan-success.window="playSuccess()"
   @scan-error.window="playError()">

    <!-- ===== MOBILE-FIRST LAYOUT ===== -->
    <!-- Mobile: Scanner takes full priority at top -->
    <div class="max-w-screen-xl mx-auto px-4 py-6 lg:py-8 lg:px-8 space-y-6">

        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <div class="flex items-center gap-2 mb-1">
                    <span class="w-2 h-5 bg-amber-500 rounded-full"></span>
                    <h1 class="text-xl lg:text-3xl font-black text-slate-900 dark:text-white tracking-tight">Global Scanner</h1>
                </div>
                <p class="text-sm text-slate-500 dark:text-slate-400 ml-4">Unified check-in for all events</p>
            </div>
            <div class="flex items-center gap-3">
                <!-- Today's Stats Pill -->
                <div class="hidden sm:flex items-center gap-3 bg-white dark:bg-slate-900 px-4 py-2.5 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm">
                    <div class="relative w-10 h-10 flex items-center justify-center flex-shrink-0">
                        <svg class="w-10 h-10 -rotate-90">
                            <circle cx="20" cy="20" r="16" stroke="currentColor" stroke-width="3" fill="transparent" class="text-slate-100 dark:text-slate-800" />
                            <circle cx="20" cy="20" r="16" stroke="currentColor" stroke-width="3" fill="transparent" 
                                    class="text-amber-500" 
                                    stroke-dasharray="100.5" 
                                    stroke-dashoffset="{{ 100.5 * (1 - ($totalTicketsToday > 0 ? $checkedInToday / $totalTicketsToday : 0)) }}" />
                        </svg>
                        <span class="absolute text-[9px] font-black text-slate-900 dark:text-white">{{ round($totalTicketsToday > 0 ? ($checkedInToday / $totalTicketsToday) * 100 : 0) }}%</span>
                    </div>
                    <div>
                        <p class="text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-widest leading-none mb-0.5">Today</p>
                        <p class="text-sm font-black text-slate-900 dark:text-white">{{ $checkedInToday }} <span class="text-slate-300 dark:text-slate-600">/</span> {{ $totalTicketsToday }}</p>
                    </div>
                </div>

                <a href="{{ route('dashboard') }}" class="w-10 h-10 bg-white dark:bg-slate-900 text-slate-600 dark:text-slate-400 rounded-xl border border-slate-200 dark:border-slate-800 flex items-center justify-center hover:bg-slate-100 dark:hover:bg-slate-800 transition-all shadow-sm" wire:navigate>
                    <span class="material-symbols-outlined text-xl">close</span>
                </a>
            </div>
        </div>

        <!-- Mobile Stats Bar (visible on small screens only) -->
        <div class="sm:hidden flex items-center gap-3 bg-white dark:bg-slate-900 px-4 py-3 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm">
            <div class="relative w-10 h-10 flex items-center justify-center flex-shrink-0">
                <svg class="w-10 h-10 -rotate-90">
                    <circle cx="20" cy="20" r="16" stroke="currentColor" stroke-width="3" fill="transparent" class="text-slate-100 dark:text-slate-800" />
                    <circle cx="20" cy="20" r="16" stroke="currentColor" stroke-width="3" fill="transparent"
                            class="text-amber-500"
                            stroke-dasharray="100.5"
                            stroke-dashoffset="{{ 100.5 * (1 - ($totalTicketsToday > 0 ? $checkedInToday / $totalTicketsToday : 0)) }}" />
                </svg>
                <span class="absolute text-[9px] font-black text-slate-900 dark:text-white">{{ round($totalTicketsToday > 0 ? ($checkedInToday / $totalTicketsToday) * 100 : 0) }}%</span>
            </div>
            <div class="flex-grow">
                <p class="text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-widest">Today's Check-ins</p>
                <p class="text-lg font-black text-slate-900 dark:text-white">{{ $checkedInToday }} <span class="text-slate-300 dark:text-slate-600 font-normal">/</span> {{ $totalTicketsToday }}</p>
            </div>
            <div class="flex items-center gap-1.5 px-3 py-1 bg-green-50 dark:bg-green-900/20 rounded-full">
                <span class="w-1.5 h-1.5 bg-green-500 rounded-full animate-pulse"></span>
                <span class="text-[10px] font-black text-green-600 dark:text-green-400">Live</span>
            </div>
        </div>

        <!-- MAIN CONTENT GRID -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 lg:gap-10">

            <!-- ===== SCANNER PANEL (Full width on mobile, 5 cols on desktop) ===== -->
            <div class="lg:col-span-5 space-y-4">
                <!-- Camera Viewfinder -->
                <div class="relative">
                    <div class="absolute -inset-1 bg-gradient-to-r from-amber-500 to-orange-500 rounded-[2.5rem] blur opacity-20"></div>
                    <div class="relative bg-slate-950 rounded-[2rem] overflow-hidden border-2 border-slate-800 shadow-2xl">
                        <!-- Scanner fills the container -->
                        <div id="reader" class="w-full" wire:ignore></div>

                        <!-- Decorative scan line overlay (pointer-events-none so it doesn't block camera) -->
                        <div class="absolute inset-0 pointer-events-none flex items-center justify-center z-10">
                            <div class="w-56 h-56 sm:w-64 sm:h-64 rounded-2xl relative overflow-hidden border border-white/10">
                                <div class="absolute top-0 left-0 w-6 h-6 border-t-2 border-l-2 border-amber-400 rounded-tl-lg"></div>
                                <div class="absolute top-0 right-0 w-6 h-6 border-t-2 border-r-2 border-amber-400 rounded-tr-lg"></div>
                                <div class="absolute bottom-0 left-0 w-6 h-6 border-b-2 border-l-2 border-amber-400 rounded-bl-lg"></div>
                                <div class="absolute bottom-0 right-0 w-6 h-6 border-b-2 border-r-2 border-amber-400 rounded-br-lg"></div>
                                <div class="absolute top-0 left-0 w-full h-0.5 bg-amber-400 shadow-[0_0_12px_rgba(245,158,11,0.8)] animate-scan-line"></div>
                            </div>
                        </div>

                        <!-- Flip Camera Button (shows only when multiple cameras available) -->
                        <div class="absolute bottom-4 right-4 z-20" x-show="cameras.length > 1">
                            <button @click="flipCamera()"
                                :disabled="isStarting"
                                class="w-12 h-12 bg-white/20 backdrop-blur-md border border-white/20 rounded-2xl flex items-center justify-center text-white hover:bg-white/30 active:scale-90 transition-all disabled:opacity-50 shadow-lg"
                                title="Switch Camera">
                                <span class="material-symbols-outlined text-2xl" :class="{ 'animate-spin': isStarting }">flip_camera_android</span>
                            </button>
                        </div>

                        <!-- Current Camera Label -->
                        <div class="absolute bottom-4 left-4 z-20" x-show="cameras.length > 0">
                            <div class="bg-black/40 backdrop-blur-sm text-white text-[10px] font-bold px-2.5 py-1.5 rounded-lg flex items-center gap-1.5">
                                <span class="material-symbols-outlined text-sm text-amber-400">videocam</span>
                                <span x-text="cameras[currentCameraIndex]?.label?.split('(')[0]?.trim()?.slice(0, 20) || 'Camera'"></span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Manual Input -->
                <div class="bg-white dark:bg-slate-900 rounded-2xl p-5 border border-slate-100 dark:border-slate-800 shadow-sm">
                    <label class="block text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-widest mb-3">Manual Ticket Code</label>
                    <div class="flex gap-2">
                        <input type="text"
                               wire:model="manualCode"
                               placeholder="Enter ticket code..."
                               class="flex-grow bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl px-4 py-3 text-sm font-bold text-slate-900 dark:text-white placeholder:text-slate-300 dark:placeholder:text-slate-600 focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 transition-all outline-none" />
                        <button wire:click="scan()"
                                class="bg-amber-500 text-white px-5 py-3 rounded-xl font-black text-sm hover:bg-amber-600 transition-all shadow-lg shadow-amber-100 dark:shadow-none active:scale-95">
                            Check
                        </button>
                    </div>
                </div>
            </div>

            <!-- ===== RESULT + ACTIVITY PANEL (7 cols on desktop) ===== -->
            <div class="lg:col-span-7 space-y-6">
                <!-- Scan Result -->
                <div class="min-h-[200px] lg:min-h-[380px]">
                    @if($scanResult)
                        <div class="animate-in fade-in zoom-in-95 duration-300 h-full">
                            @if($scanResult['success'])
                                <div class="bg-amber-500 rounded-2xl lg:rounded-[2.5rem] p-6 lg:p-10 h-full flex flex-col justify-between text-white shadow-xl shadow-amber-200/50 dark:shadow-none overflow-hidden relative">
                                    <div class="absolute -right-16 -top-16 w-48 h-48 bg-white/10 rounded-full blur-3xl"></div>
                                    <div class="absolute -left-16 -bottom-16 w-48 h-48 bg-white/10 rounded-full blur-3xl"></div>

                                    <div>
                                        <div class="flex items-center justify-between mb-5">
                                            <div class="w-12 h-12 bg-white/20 backdrop-blur-md rounded-xl flex items-center justify-center">
                                                <span class="material-symbols-outlined text-2xl">check_circle</span>
                                            </div>
                                            <div class="text-right">
                                                <p class="text-[10px] font-black uppercase tracking-widest opacity-70">Event</p>
                                                <p class="text-base font-black truncate max-w-[160px] lg:max-w-[200px]">{{ $lastTicket->event->title }}</p>
                                            </div>
                                        </div>
                                        <h2 class="text-2xl lg:text-4xl font-black mb-1 tracking-tight">{{ $lastTicket->user?->name ?? 'Guest' }}</h2>
                                        <p class="text-white/80 font-bold text-sm">{{ $lastTicket->user?->email ?? 'No email associated' }}</p>
                                    </div>

                                    <div class="bg-white/10 backdrop-blur-md rounded-2xl p-4 lg:p-6 border border-white/10 mt-5">
                                        <div class="flex justify-between items-end">
                                            <div>
                                                <p class="text-[10px] font-black uppercase tracking-widest opacity-70 mb-1">Ticket Class</p>
                                                <p class="text-base lg:text-lg font-black">{{ $lastTicket->ticketType->name }}</p>
                                            </div>
                                            <div class="text-right">
                                                <p class="text-[10px] font-black uppercase tracking-widest opacity-70 mb-1">Reference</p>
                                                <p class="font-mono font-bold text-sm">#{{ substr($lastTicket->ticket_number, -8) }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <div class="bg-red-500 rounded-2xl lg:rounded-[2.5rem] p-6 lg:p-10 h-full flex flex-col justify-center items-center text-white shadow-xl shadow-red-200/50 dark:shadow-none text-center">
                                    <div class="w-16 h-16 bg-white/20 backdrop-blur-md rounded-full flex items-center justify-center mb-5 animate-bounce">
                                        <span class="material-symbols-outlined text-4xl">no_entry</span>
                                    </div>
                                    <h2 class="text-2xl lg:text-4xl font-black mb-3 tracking-tight uppercase">Invalid Entry</h2>
                                    <p class="text-lg font-bold text-white/90 px-4">{{ $scanResult['message'] }}</p>
                                </div>
                            @endif
                        </div>
                    @else
                        <div class="bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 rounded-2xl lg:rounded-[2.5rem] h-full min-h-[200px] lg:min-h-[380px] flex flex-col items-center justify-center p-8 text-center">
                            <div class="w-20 h-20 bg-slate-50 dark:bg-slate-800 rounded-full flex items-center justify-center mb-5">
                                <span class="material-symbols-outlined text-4xl text-slate-300 dark:text-slate-600 animate-pulse">barcode_scanner</span>
                            </div>
                            <h3 class="text-xl font-black text-slate-900 dark:text-white mb-2">Scanner Active</h3>
                            <p class="text-slate-400 dark:text-slate-500 text-sm max-w-xs">Point camera at any ticket QR code. The system will automatically identify the attendee.</p>
                        </div>
                    @endif
                </div>

                <!-- Recent Activity -->
                <div class="bg-white dark:bg-slate-900 rounded-2xl lg:rounded-[2.5rem] p-5 lg:p-8 border border-slate-100 dark:border-slate-800 shadow-sm">
                    <h3 class="text-base lg:text-xl font-black text-slate-900 dark:text-white flex items-center gap-2 mb-5">
                        <span class="material-symbols-outlined text-amber-500">sensors</span>
                        Recent Check-ins
                    </h3>

                    <div class="space-y-3">
                        @php
                            $organizerId = auth()->user()->organizer->id;
                        @endphp
                        @forelse(\App\Models\Ticket::whereHas('event', fn($q) => $q->where('organizer_id', $organizerId))->where('status', 'used')->orderBy('scanned_at', 'desc')->take(5)->get() as $recent)
                            <div class="flex items-center gap-3 p-3 bg-slate-50 dark:bg-slate-800 rounded-xl border border-transparent hover:border-amber-200 dark:hover:border-amber-800 transition-all">
                                <div class="w-9 h-9 bg-white dark:bg-slate-700 rounded-lg flex items-center justify-center text-amber-500 font-black text-sm shadow-sm flex-shrink-0">
                                    {{ substr($recent->user?->name ?? 'G', 0, 1) }}
                                </div>
                                <div class="flex-grow min-w-0">
                                    <p class="text-sm font-black text-slate-900 dark:text-white truncate">{{ $recent->user?->name ?? 'Guest' }}</p>
                                    <p class="text-xs font-bold text-slate-400 dark:text-slate-500 truncate">{{ $recent->event->title }}</p>
                                </div>
                                <div class="text-right flex-shrink-0">
                                    <p class="text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase">{{ $recent->scanned_at->format('H:i') }}</p>
                                    <p class="text-[10px] font-mono text-slate-400 dark:text-slate-600">#{{ substr($recent->ticket_number, -6) }}</p>
                                </div>
                            </div>
                        @empty
                            <div class="py-10 text-center">
                                <p class="text-slate-300 dark:text-slate-700 font-black uppercase tracking-widest text-[10px]">No check-ins recorded yet</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ===== MOBILE SLIDE-UP RESULT MODAL ===== -->
    <div x-show="showModal"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="translate-y-full opacity-0"
         x-transition:enter-end="translate-y-0 opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="translate-y-0 opacity-100"
         x-transition:leave-end="translate-y-full opacity-0"
         class="fixed inset-0 z-[100] lg:hidden flex flex-col justify-end"
         style="display: none;">

        <!-- Backdrop -->
        <div class="absolute inset-0 bg-slate-900/70 backdrop-blur-sm" @click="showModal = false"></div>

        <!-- Drawer Card -->
        <div class="relative bg-white dark:bg-slate-900 rounded-t-[2.5rem] p-6 shadow-2xl flex flex-col gap-5 max-h-[85vh] overflow-y-auto">
            <!-- Handle -->
            <div class="w-10 h-1 bg-slate-200 dark:bg-slate-700 rounded-full mx-auto -mt-2"></div>

            @if($scanResult)
                @if($scanResult['success'])
                    <!-- SUCCESS MODAL CONTENT -->
                    <div class="flex flex-col items-center text-center">
                        <div class="w-20 h-20 bg-amber-500 rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-xl shadow-amber-200/50">
                            <span class="material-symbols-outlined text-white text-4xl">check_circle</span>
                        </div>
                        <h2 class="text-3xl font-black text-slate-900 dark:text-white uppercase tracking-tight mb-1">Verified ✓</h2>
                        <p class="text-slate-500 dark:text-slate-400 font-bold text-sm">{{ $lastTicket->event->title }}</p>
                    </div>

                    <div class="bg-slate-50 dark:bg-slate-800 rounded-2xl p-5 border border-slate-100 dark:border-slate-700">
                        <p class="text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-widest mb-1">Attendee</p>
                        <p class="text-2xl font-black text-slate-900 dark:text-white mb-4">{{ $lastTicket->user?->name ?? 'Guest' }}</p>

                        <div class="grid grid-cols-2 gap-4 pt-4 border-t border-slate-200 dark:border-slate-700">
                            <div>
                                <p class="text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-widest">Type</p>
                                <p class="font-bold text-slate-900 dark:text-white">{{ $lastTicket->ticketType->name }}</p>
                            </div>
                            <div>
                                <p class="text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-widest">Ref</p>
                                <p class="font-bold font-mono text-slate-900 dark:text-white">#{{ substr($lastTicket->ticket_number, -6) }}</p>
                            </div>
                        </div>
                    </div>
                @else
                    <!-- ERROR MODAL CONTENT -->
                    <div class="flex flex-col items-center text-center">
                        <div class="w-20 h-20 bg-red-500 rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-xl shadow-red-200/50">
                            <span class="material-symbols-outlined text-white text-4xl">block</span>
                        </div>
                        <h2 class="text-3xl font-black text-red-600 dark:text-red-400 uppercase tracking-tight mb-2">Entry Denied</h2>
                        <p class="text-slate-900 dark:text-white text-lg font-bold px-4">{{ $scanResult['message'] }}</p>
                    </div>
                @endif
            @endif

            <button @click="showModal = false"
                    class="w-full bg-slate-900 dark:bg-white text-white dark:text-slate-900 py-4 rounded-2xl font-black text-base shadow-xl active:scale-95 transition-all">
                Next Scan →
            </button>
        </div>
    </div>

    <!-- Scanner Styles -->
    <style>
        #reader {
            border: none !important;
            background: transparent !important;
        }
        #reader video {
            width: 100% !important;
            height: auto !important;
            min-height: 280px !important;
            object-fit: cover !important;
            border-radius: 0 !important;
        }
        #reader__scan_region {
            background: transparent !important;
        }
        #reader__dashboard_section_csr button {
            background-color: #f59e0b !important;
            color: white !important;
            padding: 0.75rem 1.5rem !important;
            border-radius: 0.75rem !important;
            font-weight: 800 !important;
            border: none !important;
            text-transform: uppercase !important;
            font-size: 0.75rem !important;
            letter-spacing: 0.05em !important;
            margin: 0.75rem !important;
            cursor: pointer !important;
            width: calc(100% - 1.5rem) !important;
        }
        #reader__dashboard_section_csr button:active {
            transform: scale(0.97);
        }
        #reader__camera_selection {
            background: #1e293b !important;
            color: white !important;
            border: 1px solid #334155 !important;
            border-radius: 0.5rem !important;
            padding: 0.5rem !important;
            margin: 0.5rem !important;
            font-size: 0.875rem !important;
        }
        #reader__header_message {
            color: #94a3b8 !important;
            font-size: 0.75rem !important;
            padding: 0.5rem !important;
        }
        @keyframes scan-line {
            0% { top: 0; opacity: 1; }
            100% { top: 100%; opacity: 0.3; }
        }
        .animate-scan-line {
            animation: scan-line 1.8s ease-in-out infinite alternate;
        }
    </style>

    <!-- QR Scanner Script -->
    <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
</div>
