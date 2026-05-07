<div
    x-data="{
        secondsLeft: {{ $reservationSecondsRemaining ?? 0 }},
        timerInterval: null,
        hasExpiredDuringSession: false,
        init() {
            if (this.secondsLeft > 0) {
                this.timerInterval = setInterval(() => {
                    if (this.secondsLeft > 0) {
                        this.secondsLeft--;
                        if (this.secondsLeft === 0) {
                            this.hasExpiredDuringSession = true;
                            clearInterval(this.timerInterval);
                        }
                    }
                }, 1000);
            }
        },
        get minutes() { return String(Math.floor(this.secondsLeft / 60)).padStart(2, '0'); },
        get seconds() { return String(this.secondsLeft % 60).padStart(2, '0'); },
        get isUrgent() { return this.secondsLeft <= 120 && this.secondsLeft > 0; },
        get isExpired() { return this.hasExpiredDuringSession && this.secondsLeft === 0; }
    }">

    {{-- ── Session expiry flash ── --}}
    @if(session('error'))
    <div class="bg-red-50 border-l-4 border-red-500 text-red-700 px-6 py-4 mb-6 rounded-xl flex items-center gap-3">
        <span class="material-symbols-outlined">error</span>
        <p class="font-medium">{{ session('error') }}</p>
    </div>
    @endif

    <div class="max-w-screen-xl mx-auto w-full px-6 py-12">
        <!-- Progress Stepper (Inspired by my_tickets.zip design) -->
        <div class="mb-12 max-w-3xl mx-auto">
            <div class="flex items-center justify-between relative">
                <div class="absolute top-1/2 left-0 w-full h-1 bg-slate-100 -translate-y-1/2 -z-10"></div>
                <div class="absolute top-1/2 left-0 h-1 bg-[#4f46e5] -translate-y-1/2 -z-10 transition-all duration-500" 
                     style="width: {{ ($step - 1) * 33.33 }}%;"></div>
                
                <!-- Step 1: Selection -->
                <div class="flex flex-col items-center gap-2 bg-[#f7f9fb] px-4">
                    <div class="w-10 h-10 rounded-full flex items-center justify-center shadow-md {{ $step >= 1 ? 'bg-[#4f46e5] text-white' : 'bg-white text-slate-400 border-2 border-slate-200' }}">
                        @if($step > 1) <span class="material-symbols-outlined text-sm">check</span> @else <span class="text-sm font-bold">1</span> @endif
                    </div>
                    <span class="text-[10px] uppercase font-bold tracking-wider {{ $step >= 1 ? 'text-[#4f46e5]' : 'text-slate-400' }}">Selection</span>
                </div>

                <!-- Step 2: Details -->
                <div class="flex flex-col items-center gap-2 bg-[#f7f9fb] px-4">
                    <div class="w-10 h-10 rounded-full flex items-center justify-center shadow-sm {{ $step >= 2 ? 'bg-[#4f46e5] text-white' : 'bg-white text-slate-400 border-2 border-slate-200' }}">
                        @if($step > 2) <span class="material-symbols-outlined text-sm">check</span> @else <span class="text-sm font-bold">2</span> @endif
                    </div>
                    <span class="text-[10px] uppercase font-bold tracking-wider {{ $step >= 2 ? 'text-[#4f46e5]' : 'text-slate-400' }}">Details</span>
                </div>

                <!-- Step 3: Review -->
                <div class="flex flex-col items-center gap-2 bg-[#f7f9fb] px-4">
                    <div class="w-10 h-10 rounded-full flex items-center justify-center shadow-sm {{ $step >= 3 ? 'bg-[#4f46e5] text-white' : 'bg-white text-slate-400 border-2 border-slate-200' }}">
                        @if($step > 3) <span class="material-symbols-outlined text-sm">check</span> @else <span class="text-sm font-bold">3</span> @endif
                    </div>
                    <span class="text-[10px] uppercase font-bold tracking-wider {{ $step >= 3 ? 'text-[#4f46e5]' : 'text-slate-400' }}">Review</span>
                </div>

                <!-- Step 4: Payment -->
                <div class="flex flex-col items-center gap-2 bg-[#f7f9fb] px-4">
                    <div class="w-10 h-10 rounded-full flex items-center justify-center shadow-sm {{ $step >= 4 ? 'bg-[#4f46e5] text-white' : 'bg-white text-slate-400 border-2 border-slate-200' }}">
                        <span class="text-sm font-bold">4</span>
                    </div>
                    <span class="text-[10px] uppercase font-bold tracking-wider {{ $step >= 4 ? 'text-[#4f46e5]' : 'text-slate-400' }}">Payment</span>
                </div>
            </div>
        </div>

        {{-- ── Reservation Countdown Banner ── --}}
        @if($reservationId && $step > 1)
        <div x-show="secondsLeft > 0"
             :class="isUrgent ? 'bg-amber-50 border-amber-400 text-amber-800' : 'bg-emerald-50 border-emerald-400 text-emerald-800'"
             class="mb-8 border-2 rounded-2xl px-6 py-4 flex items-center justify-between gap-4 transition-all duration-500">
            <div class="flex items-center gap-3">
                <span class="material-symbols-outlined text-2xl" :class="isUrgent ? 'text-amber-500' : 'text-emerald-500'" style="font-variation-settings: 'FILL' 1;">timer</span>
                <div>
                    <p class="font-bold text-sm">🎟️ Your tickets are reserved!</p>
                    <p class="text-xs opacity-75">Complete your purchase before the timer runs out.</p>
                </div>
            </div>
            <div class="font-mono text-2xl font-black tracking-widest"
                 :class="isUrgent ? 'text-amber-600 animate-pulse' : 'text-emerald-700'">
                <span x-text="minutes"></span>:<span x-text="seconds"></span>
            </div>
        </div>
        <div x-show="isExpired"
             class="mb-8 bg-red-50 border-2 border-red-400 text-red-800 rounded-2xl px-6 py-4 flex items-center gap-3">
            <span class="material-symbols-outlined text-red-500 text-2xl">timer_off</span>
            <div>
                <p class="font-bold">Your reservation has expired.</p>
                <p class="text-sm opacity-75">Please go back to Step 1 and select your tickets again.</p>
            </div>
        </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12">
            <!-- Left Column: Step Content -->
            <div class="lg:col-span-7 space-y-8">
                
                @if($step === 1)
                <!-- Step 1: Select Ticket & Quantity -->
                <section class="bg-white rounded-2xl border border-slate-200 p-8 shadow-sm">
                    <h2 class="text-2xl font-bold text-slate-900 mb-6">Select your tickets</h2>
                    <div class="space-y-4">
                        @foreach($event->ticketTypes as $ticketType)
                        <label class="block cursor-pointer group">
                            <div class="border-2 rounded-xl p-5 transition-all flex items-center justify-between {{ $selectedTicketTypeId == $ticketType->id ? 'border-[#4f46e5] bg-indigo-50/30' : 'border-slate-100 hover:border-slate-200' }}">
                                <div class="flex items-center gap-4">
                                    <input type="radio" name="ticket_type" value="{{ $ticketType->id }}" wire:model.live="selectedTicketTypeId" class="text-[#4f46e5] focus:ring-[#4f46e5]" />
                                    <div>
                                        <p class="font-bold text-slate-900">{{ $ticketType->name }}</p>
                                        @php
                                            $availableQty = app(\App\Services\TicketReservationService::class)->getAvailableQuantity($ticketType, auth()->id());
                                        @endphp
                                        <p class="text-xs text-slate-500">{{ $availableQty }} available • Max {{ $ticketType->max_per_order ?? 10 }} per order</p>
                                        @if($availableQty === 0)
                                            <span class="inline-block mt-1 text-[10px] font-bold uppercase text-amber-600 bg-amber-50 px-2 py-0.5 rounded-full">Temporarily Held</span>
                                        @endif
                                    </div>
                                </div>
                                <span class="text-lg font-bold text-[#4f46e5]">€{{ number_format($ticketType->price, 2) }}</span>
                            </div>
                        </label>
                        @endforeach
                        @error('selectedTicketTypeId') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="mt-8">
                        <label class="block text-sm font-bold text-slate-700 mb-2">Quantity</label>
                        <div class="flex items-center gap-4">
                            <button wire:click="$set('quantity', {{ max(1, $quantity - 1) }})" class="w-10 h-10 rounded-lg border border-slate-200 flex items-center justify-center hover:bg-slate-50">-</button>
                            <input type="number" wire:model.live="quantity" class="w-20 text-center border-slate-200 rounded-lg font-bold" min="1" />
                            <button wire:click="$set('quantity', {{ $quantity + 1 }})" class="w-10 h-10 rounded-lg border border-slate-200 flex items-center justify-center hover:bg-slate-50">+</button>
                        </div>
                        @error('quantity') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </section>
                @endif

                @if($step === 2)
                <!-- Step 2: Customer Info -->
                <section class="bg-white rounded-2xl border border-slate-200 p-8 shadow-sm">
                    <h2 class="text-2xl font-bold text-slate-900 mb-6">Customer Information</h2>
                    <div class="space-y-6">
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Full Name</label>
                            <input type="text" wire:model.live="name" placeholder="Johnathan Doe" class="w-full border-slate-200 rounded-xl p-3 focus:ring-2 focus:ring-indigo-100 focus:border-[#4f46e5] outline-none" />
                            @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Email Address</label>
                            <input type="email" wire:model.live="email" placeholder="john@example.com" class="w-full border-slate-200 rounded-xl p-3 focus:ring-2 focus:ring-indigo-100 focus:border-[#4f46e5] outline-none" />
                            <p class="text-[10px] text-slate-400 mt-2">Your tickets will be sent to this email address.</p>
                            @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </section>
                @endif

                @if($step === 3)
                <!-- Step 3: Review Order -->
                <section class="bg-white rounded-2xl border border-slate-200 p-8 shadow-sm">
                    <h2 class="text-2xl font-bold text-slate-900 mb-6">Review your order</h2>
                    <div class="space-y-6">
                        <div class="flex items-center gap-4 p-4 bg-slate-50 rounded-xl border border-slate-100">
                            <div class="w-12 h-12 rounded-lg bg-white flex items-center justify-center shadow-sm">
                                <span class="material-symbols-outlined text-[#4f46e5]">person</span>
                            </div>
                            <div>
                                <p class="text-xs text-slate-500 uppercase font-bold">Ticket Holder</p>
                                <p class="font-bold text-slate-900">{{ $name }}</p>
                                <p class="text-xs text-slate-600">{{ $email }}</p>
                            </div>
                        </div>

                        <div class="p-6 border border-slate-100 rounded-xl space-y-4">
                            <div class="flex justify-between items-center">
                                <span class="text-slate-600">Ticket Type</span>
                                <span class="font-bold">{{ $this->selectedTicketType->name }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-slate-600">Quantity</span>
                                <span class="font-bold">× {{ $quantity }}</span>
                            </div>
                            <div class="flex justify-between items-center pt-4 border-t border-slate-100">
                                <span class="text-slate-900 font-bold">Subtotal</span>
                                <span class="text-lg font-bold">€{{ number_format($this->subtotal, 2) }}</span>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Have a coupon?</label>
                            <div class="flex gap-2">
                                <input type="text" wire:model="couponCode" placeholder="Enter code" class="flex-grow border-slate-200 rounded-xl p-3 focus:ring-2 focus:ring-indigo-100 focus:border-[#4f46e5] outline-none" />
                                <button wire:click="applyCoupon" class="bg-slate-900 text-white px-6 py-3 rounded-xl font-bold hover:bg-slate-800 transition-all">Apply</button>
                            </div>
                            @error('couponCode') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            @if($appliedCoupon) <p class="text-green-600 text-xs mt-1">Coupon "{{ $appliedCoupon->code }}" applied!</p> @endif
                        </div>
                    </div>
                </section>
                @endif

                @if($step === 4)
                <!-- Step 4: Final Payment (Placeholder for Phase 14) -->
                <section class="bg-white rounded-2xl border border-slate-200 p-8 shadow-sm text-center">
                    <div class="w-16 h-16 bg-indigo-50 rounded-full flex items-center justify-center mx-auto mb-6">
                        <span class="material-symbols-outlined text-[#4f46e5] text-3xl">payment</span>
                    </div>
                    <h2 class="text-2xl font-bold text-slate-900 mb-2">Ready to Pay</h2>
                    <p class="text-slate-500 mb-8 max-w-md mx-auto">Please review your order one last time. You will be redirected to our secure payment processor.</p>
                    
                    <div class="bg-slate-50 rounded-xl p-6 mb-8 text-left">
                        <div class="flex justify-between mb-2">
                            <span class="text-slate-600">Total Amount Due</span>
                            <span class="text-2xl font-bold text-[#4f46e5]">€{{ number_format($this->total, 2) }}</span>
                        </div>
                        <p class="text-[10px] text-slate-400">By clicking "Pay Now", you agree to our Terms of Service and Privacy Policy.</p>
                    </div>
                </section>
                @endif

                <!-- Navigation Buttons -->
                <div class="flex items-center justify-between pt-8">
                    @if($step > 1)
                    <button wire:click="prevStep" class="flex items-center gap-2 font-bold text-slate-500 hover:text-slate-900 transition-colors">
                        <span class="material-symbols-outlined text-sm">arrow_back</span> Back
                    </button>
                    @else
                    <div></div>
                    @endif

                    @if($step < 4)
                    <button wire:click="nextStep" class="bg-[#4f46e5] text-white px-10 py-4 rounded-xl font-bold shadow-lg shadow-indigo-100 hover:bg-[#4f46e5]/90 transition-all active:scale-95 flex items-center gap-2">
                        Continue <span class="material-symbols-outlined text-sm">arrow_forward</span>
                    </button>
                    @else
                    <button wire:click="pay" wire:loading.attr="disabled" class="bg-[#4f46e5] text-white px-12 py-4 rounded-xl font-bold shadow-lg shadow-indigo-200 hover:bg-[#4f46e5]/90 transition-all active:scale-95 disabled:opacity-50">
                        <span wire:loading.remove>Pay Now (€{{ number_format($this->total, 2) }})</span>
                        <span wire:loading>Processing...</span>
                    </button>
                    @endif
                </div>
            </div>

            <!-- Right Column: Event Info & Summary Sidebar -->
            <aside class="lg:col-span-5 space-y-6">
                <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-sm sticky top-24">
                    <div class="h-32 relative">
                        @if($event->image)
                            <img src="{{ Storage::url($event->image) }}" class="w-full h-full object-cover" />
                        @else
                            <div class="w-full h-full bg-gradient-to-br from-indigo-500 to-purple-600"></div>
                        @endif
                        <div class="absolute inset-0 bg-slate-900/40"></div>
                        <div class="absolute bottom-4 left-4 right-4">
                            <p class="text-white text-xs font-bold uppercase tracking-widest opacity-80 mb-1">{{ $event->category }}</p>
                            <h3 class="text-white font-bold leading-tight">{{ $event->title }}</h3>
                        </div>
                    </div>

                    <div class="p-6 space-y-6">
                        <div class="space-y-3">
                            <div class="flex items-center gap-3 text-sm text-slate-600">
                                <span class="material-symbols-outlined text-slate-400 text-sm">calendar_today</span>
                                {{ $event->start_date->format('l, d M Y') }}
                            </div>
                            <div class="flex items-center gap-3 text-sm text-slate-600">
                                <span class="material-symbols-outlined text-slate-400 text-sm">location_on</span>
                                {{ $event->venue?->name ?? $event->city }}
                            </div>
                        </div>

                        <hr class="border-slate-100" />

                        <div class="space-y-4">
                            <h4 class="text-sm font-bold text-slate-900 uppercase tracking-widest">Order Summary</h4>
                            @if($this->selectedTicketType)
                            <div class="flex justify-between items-center">
                                <div class="text-sm">
                                    <p class="text-slate-900 font-bold">{{ $this->selectedTicketType->name }}</p>
                                    <p class="text-slate-500">Qty: {{ $quantity }}</p>
                                </div>
                                <span class="font-bold text-slate-900">€{{ number_format($this->subtotal, 2) }}</span>
                            </div>
                            @endif

                            @if($discount > 0)
                            <div class="flex justify-between items-center text-green-600">
                                <span class="text-sm font-medium">Discount</span>
                                <span class="font-bold">- €{{ number_format($discount, 2) }}</span>
                            </div>
                            @endif

                            <div class="flex justify-between items-center pt-4 border-t border-slate-100">
                                <span class="text-lg font-bold text-slate-900">Total</span>
                                <span class="text-2xl font-bold text-[#4f46e5]">€{{ number_format($this->total, 2) }}</span>
                            </div>
                        </div>

                        <!-- Trust Indicators -->
                        <div class="pt-6 grid grid-cols-2 gap-4">
                            <div class="bg-slate-50 p-3 rounded-xl flex items-center gap-3">
                                <span class="material-symbols-outlined text-[#4f46e5] text-xl" style="font-variation-settings: 'FILL' 1;">lock</span>
                                <div class="flex flex-col">
                                    <span class="text-[8px] uppercase font-bold text-slate-400">Secure</span>
                                    <span class="text-[10px] font-bold text-slate-900">SSL</span>
                                </div>
                            </div>
                            <div class="bg-slate-50 p-3 rounded-xl flex items-center gap-3">
                                <span class="material-symbols-outlined text-[#4f46e5] text-xl" style="font-variation-settings: 'FILL' 1;">verified_user</span>
                                <div class="flex flex-col">
                                    <span class="text-[8px] uppercase font-bold text-slate-400">Verified</span>
                                    <span class="text-[10px] font-bold text-slate-900">Buyer</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </aside>
        </div>
    </div>
</div>
