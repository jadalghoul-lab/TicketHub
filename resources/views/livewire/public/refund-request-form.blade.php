<div>
    @php
        $canRequest = !$order->event->start_date->isPast() && $order->status === 'paid';
        $refundRequest = \App\Models\RefundRequest::where('order_id', $order->id)->first();
    @endphp

    @if($refundRequest)
        @if($refundRequest->status === 'pending')
            <div class="bg-amber-50 border border-amber-100 p-4 rounded-2xl flex items-center gap-3">
                <span class="material-symbols-outlined text-amber-600">hourglass_empty</span>
                <p class="text-xs font-bold text-amber-800">Refund request is pending approval.</p>
            </div>
        @elseif($refundRequest->status === 'approved')
            <div class="bg-green-50 border border-green-100 p-4 rounded-2xl flex items-center gap-3">
                <span class="material-symbols-outlined text-green-600">check_circle</span>
                <p class="text-xs font-bold text-green-800">Refund request was approved.</p>
            </div>
        @elseif($refundRequest->status === 'rejected')
            <div class="bg-red-50 border border-red-100 p-4 rounded-2xl flex items-center gap-3">
                <span class="material-symbols-outlined text-red-600">cancel</span>
                <p class="text-xs font-bold text-red-800">Refund request was rejected.</p>
            </div>
        @endif
    @elseif($canRequest)
        <button wire:click="$set('showModal', true)" class="w-full bg-red-50 text-red-600 px-6 py-4 rounded-2xl font-bold hover:bg-red-600 hover:text-white transition-all flex items-center justify-center gap-3 shadow-sm active:scale-95">
            <span class="material-symbols-outlined">undo</span>
            Request Refund
        </button>
    @endif

    @if($showModal)
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-50 flex items-center justify-center p-4">
            <div class="bg-white rounded-[2.5rem] w-full max-w-lg p-10 shadow-2xl animate-in fade-in zoom-in duration-300">
                <h3 class="text-2xl font-black text-slate-900 mb-2">Request Refund</h3>
                <p class="text-slate-500 text-sm mb-8">Please tell us why you'd like a refund. Our team will review your request shortly.</p>

                <form wire:submit.prevent="submit" class="space-y-6">
                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">Reason for Refund</label>
                        <textarea wire:model="reason" rows="4" class="w-full bg-slate-50 border border-slate-100 rounded-2xl p-4 text-slate-900 font-medium focus:ring-2 focus:ring-indigo-100 outline-none transition-all" placeholder="Tell us more..."></textarea>
                        @error('reason') <p class="text-red-500 text-[10px] font-bold mt-2 ml-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="flex gap-4 pt-4">
                        <button type="button" wire:click="$set('showModal', false)" class="flex-1 px-6 py-4 rounded-2xl font-bold text-slate-500 hover:bg-slate-50 transition-all">Cancel</button>
                        <button type="submit" class="flex-1 bg-indigo-600 text-white px-6 py-4 rounded-2xl font-bold shadow-lg shadow-indigo-100 hover:bg-indigo-700 transition-all active:scale-95">Submit Request</button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    @if (session()->has('success'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)" class="fixed bottom-10 right-10 bg-green-600 text-white px-8 py-4 rounded-2xl shadow-2xl font-bold animate-bounce z-[100]">
            {{ session('success') }}
        </div>
    @endif

    @if (session()->has('error'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)" class="fixed bottom-10 right-10 bg-red-600 text-white px-8 py-4 rounded-2xl shadow-2xl font-bold animate-shake z-[100]">
            {{ session('error') }}
        </div>
    @endif
</div>
