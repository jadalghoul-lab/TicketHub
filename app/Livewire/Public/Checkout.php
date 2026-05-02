<?php

namespace App\Livewire\Public;

use App\Models\Event;
use App\Models\TicketType;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\Ticket;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Stripe\Stripe;
use Stripe\Checkout\Session;

class Checkout extends Component
{
    public Event $event;
    public $step = 1;

    // Step 1: Selection
    public $selectedTicketTypeId;
    public $quantity = 1;

    // Step 2: Customer Info
    public $name;
    public $email;

    // Step 3: Review & Coupon
    public $couponCode;
    public $discount = 0;
    public $appliedCoupon = null;
    public $currentOrder;

    public function rules()
    {
        $allRules = [
            'selectedTicketTypeId' => 'required|exists:ticket_types,id',
            'quantity' => 'required|integer|min:1',
            'name' => 'required|string|min:2',
            'email' => 'required|email',
        ];

        if ($this->step === 1) {
            return [
                'selectedTicketTypeId' => $allRules['selectedTicketTypeId'],
                'quantity' => $allRules['quantity'],
            ];
        }

        if ($this->step === 2) {
            return [
                'name' => $allRules['name'],
                'email' => $allRules['email'],
            ];
        }

        return $allRules;
    }

    public function mount($slug)
    {
        $this->event = Event::published()->where('slug', $slug)->firstOrFail();
        
        // Pre-select first ticket type if available
        if ($this->event->ticketTypes->count() > 0) {
            $this->selectedTicketTypeId = $this->event->ticketTypes->first()->id;
        }

        if (auth()->check()) {
            $this->name = auth()->user()->name;
            $this->email = auth()->user()->email;
        }
    }

    public function nextStep()
    {
        $this->validate();

        // Custom validation for step 1
        if ($this->step === 1) {
            $ticketType = TicketType::find($this->selectedTicketTypeId);
            if ($ticketType->quantity < $this->quantity) {
                $this->addError('quantity', 'Not enough tickets available.');
                return;
            }
            if ($ticketType->max_per_order && $this->quantity > $ticketType->max_per_order) {
                $this->addError('quantity', "Maximum {$ticketType->max_per_order} tickets allowed per order.");
                return;
            }
        }

        $this->step++;
    }

    public function prevStep()
    {
        $this->step--;
    }

    public function applyCoupon()
    {
        $this->validate(['couponCode' => 'required']);

        $coupon = Coupon::where('code', $this->couponCode)
            ->where(function ($q) {
                $q->whereNull('expires_at')->orWhere('expires_at', '>', now());
            })
            ->where(function ($q) {
                $q->whereNull('max_usages')->orWhereColumn('usages_count', '<', 'max_usages');
            })
            ->first();

        if (!$coupon) {
            $this->addError('couponCode', 'Invalid or expired coupon.');
            return;
        }

        $this->appliedCoupon = $coupon;
        
        if ($coupon->type === 'percentage') {
            $this->discount = $coupon->value;
        } else {
            // Convert fixed to percentage for the simplified logic I wrote
            // Or just calculate fixed discount
            $this->discount = ($coupon->value / $this->subtotal) * 100;
        }

        session()->flash('coupon_applied', 'Coupon applied successfully!');
    }

    public function getSelectedTicketTypeProperty()
    {
        return TicketType::find($this->selectedTicketTypeId);
    }

    public function getSubtotalProperty()
    {
        if (!$this->selectedTicketType) return 0;
        return $this->selectedTicketType->price * $this->quantity;
    }

    public function getTotalProperty()
    {
        $total = $this->subtotal;
        if ($this->discount > 0) {
            $total = $total - ($total * ($this->discount / 100));
        }
        return $total;
    }

    public function pay()
    {
        $this->validate();

        \Illuminate\Support\Facades\DB::transaction(function () {
            Stripe::setApiKey(config('services.stripe.secret'));

            // 1. Create Pending Order
            $this->currentOrder = Order::create([
                'organizer_id' => $this->event->organizer_id,
                'event_id' => $this->event->id,
                'user_id' => auth()->id(),
                'order_number' => 'ORD-' . strtoupper(\Illuminate\Support\Str::random(8)),
                'total_amount' => $this->total,
                'status' => 'pending',
            ]);

            // 2. Create Order Items
            \App\Models\OrderItem::create([
                'order_id' => $this->currentOrder->id,
                'ticket_type_id' => $this->selectedTicketTypeId,
                'quantity' => $this->quantity,
                'unit_price' => $this->selectedTicketType->price,
                'subtotal' => $this->subtotal,
            ]);
        });

        $order = $this->currentOrder;

        // 3. Create Stripe Session
        $session = Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price_data' => [
                    'currency' => 'eur',
                    'product_data' => [
                        'name' => "{$this->event->title} - {$this->selectedTicketType->name}",
                    ],
                    'unit_amount' => $this->total * 100,
                ],
                'quantity' => 1, // We already calculated total for the whole quantity
            ]],
            'mode' => 'payment',
            'success_url' => route('public.checkout.success', $order->order_number),
            'cancel_url' => route('public.checkout', $this->event->slug) . '?cancelled=1',
            'customer_email' => $this->email,
            'metadata' => [
                'order_id' => $order->id,
                'event_id' => $this->event->id,
                'ticket_type_id' => $this->selectedTicketTypeId,
                'quantity' => $this->quantity,
            ],
        ]);

        $order->update(['payment_intent_id' => $session->id]); // Using session ID as reference for now

        return redirect($session->url);
    }

    #[Layout('layouts.public')]
    public function render()
    {
        return view('livewire.public.checkout');
    }
}
