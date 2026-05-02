<div class="max-w-4xl mx-auto p-6" x-data="{ 
    scanner: null,
    init() {
        this.startScanner();
    },
    startScanner() {
        this.scanner = new Html5QrcodeScanner('reader', { 
            fps: 15, 
            qrbox: {width: 250, height: 250},
            rememberLastUsedCamera: true,
            aspectRatio: 1.0,
            showTorchButtonIfSupported: true
        });
        this.scanner.render((decodedText) => {
            if (this.isCooldown) return;
            this.isCooldown = true;
            $wire.scan(decodedText).then(() => {
                setTimeout(() => this.isCooldown = false, 2000);
            });
        });
    },
    playSuccess() {
        let audio = new Audio('https://assets.mixkit.co/active_storage/sfx/2568/2568-preview.mp3');
        audio.play();
    },
    playError() {
        let audio = new Audio('https://assets.mixkit.co/active_storage/sfx/2571/2571-preview.mp3');
        audio.play();
    },
    isCooldown: false,
    resetScanner() {
//...
    }
}" @reset-result.window="setTimeout(() => $wire.scanResult = null, 5000)"
   @scan-success.window="playSuccess()"
   @scan-error.window="playError()">
    
    <div class="mb-8 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">Ticket Scanner</h1>
            <p class="text-sm text-slate-500">{{ $event->title }}</p>
        </div>
        <a href="{{ route('dashboard') }}" class="text-sm font-semibold text-indigo-600 hover:text-indigo-500" wire:navigate>Back to Dashboard</a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        <!-- Scanner Panel -->
        <div class="space-y-6">
            <div class="bg-white rounded-3xl border-4 border-slate-100 overflow-hidden shadow-xl">
                <div id="reader" class="w-full aspect-square bg-slate-900" wire:ignore></div>
            </div>

            <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm">
                <label class="block text-sm font-bold text-slate-700 mb-2">Manual Entry</label>
                <div class="flex gap-2">
                    <input type="text" wire:model="manualCode" placeholder="Enter UUID or Ticket #" class="flex-grow border-slate-200 rounded-xl p-3 focus:ring-2 focus:ring-indigo-100 focus:border-indigo-600 outline-none" />
                    <button wire:click="scan()" class="bg-slate-900 text-white px-6 py-3 rounded-xl font-bold hover:bg-slate-800 transition-all">Check</button>
                </div>
            </div>
        </div>

        <!-- Results Panel -->
        <div class="space-y-6">
            @if($scanResult)
                <div class="animate-in fade-in slide-in-from-bottom-4 duration-500">
                    @if($scanResult['success'])
                        <div class="bg-green-50 border-2 border-green-200 rounded-3xl p-8 text-center shadow-lg shadow-green-100">
                            <div class="w-20 h-20 bg-green-500 rounded-full flex items-center justify-center mx-auto mb-6 text-white shadow-lg shadow-green-200">
                                <span class="material-symbols-outlined text-4xl">check</span>
                            </div>
                            <h2 class="text-2xl font-black text-green-900 mb-2">ACCESS GRANTED</h2>
                            <p class="text-green-700 font-bold mb-6">{{ $scanResult['message'] }}</p>
                            
                            @if($lastTicket)
                                <div class="bg-white rounded-2xl p-6 text-left border border-green-100">
                                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Ticket Holder</p>
                                    <p class="text-lg font-bold text-slate-900">{{ $lastTicket->user?->name ?? 'Guest' }}</p>
                                    <p class="text-sm text-slate-500 mb-4">{{ $lastTicket->user?->email ?? 'No email' }}</p>
                                    
                                    <div class="flex justify-between items-center pt-4 border-t border-slate-50">
                                        <span class="bg-indigo-50 text-indigo-700 text-[10px] font-black px-3 py-1 rounded-full uppercase">{{ $lastTicket->ticketType->name }}</span>
                                        <span class="text-xs font-bold text-slate-400">#{{ $lastTicket->ticket_number }}</span>
                                    </div>
                                </div>
                            @endif
                        </div>
                    @else
                        <div class="bg-red-50 border-2 border-red-200 rounded-3xl p-8 text-center shadow-lg shadow-red-100">
                            <div class="w-20 h-20 bg-red-500 rounded-full flex items-center justify-center mx-auto mb-6 text-white shadow-lg shadow-red-200">
                                <span class="material-symbols-outlined text-4xl">close</span>
                            </div>
                            <h2 class="text-2xl font-black text-red-900 mb-2">ACCESS DENIED</h2>
                            <p class="text-red-700 font-bold">{{ $scanResult['message'] }}</p>
                        </div>
                    @endif
                </div>
            @else
                <div class="bg-slate-50 border-2 border-dashed border-slate-200 rounded-3xl p-12 text-center flex flex-col items-center justify-center min-h-[400px]">
                    <span class="material-symbols-outlined text-6xl text-slate-200 mb-4 animate-pulse">qr_code_scanner</span>
                    <p class="text-slate-400 font-bold">Waiting for scan...</p>
                    <p class="text-[10px] text-slate-300 uppercase tracking-widest mt-2">Position the QR code within the frame</p>
                </div>
            @endif

            <!-- Recent Scans List (Mini) -->
            <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm">
                <h3 class="font-bold text-slate-900 mb-4 flex items-center gap-2">
                    <span class="material-symbols-outlined text-sm">history</span>
                    Recent Activity
                </h3>
                <div class="space-y-4">
                    @forelse(\App\Models\Ticket::where('event_id', $event->id)->where('status', 'used')->orderBy('scanned_at', 'desc')->take(5)->get() as $recent)
                        <div class="flex items-center justify-between py-2 border-b border-slate-50 last:border-0">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 bg-slate-100 rounded-full flex items-center justify-center">
                                    <span class="material-symbols-outlined text-slate-400 text-xs">person</span>
                                </div>
                                <div>
                                    <p class="text-xs font-bold text-slate-900">{{ $recent->user?->name ?? 'Guest' }}</p>
                                    <p class="text-[10px] text-slate-400">{{ $recent->scanned_at->diffForHumans() }}</p>
                                </div>
                            </div>
                            <span class="text-[10px] font-bold text-slate-400">#{{ $recent->ticket_number }}</span>
                        </div>
                    @empty
                        <p class="text-center text-xs text-slate-400 py-4">No scans yet today.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
</div>
