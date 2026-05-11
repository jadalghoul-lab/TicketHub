<?php

namespace App\Livewire\Public;

use App\Models\Event;
use App\Models\TicketType;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\Ticket;
use App\Models\TicketReservation;
use App\Services\TicketReservationService;
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

    // Reservation tracking
    public ?int $reservationId = null;
    public ?string $reservationExpiresAt = null;

    public function rules()
    {
        $allRules = [
            'selectedTicketTypeId' => 'required|exists:ticket_types,id',
            'quantity'             => 'required|integer|min:1',
            'name'                 => 'required|string|min:2',
            'email'                => 'required|email',
        ];

        if ($this->step === 1) {
            return [
                'selectedTicketTypeId' => $allRules['selectedTicketTypeId'],
                'quantity'             => $allRules['quantity'],
            ];
        }

        if ($this->step === 2) {
            return [
                'name'  => $allRules['name'],
                'email' => $allRules['email'],
            ];
        }

        return $allRules;
    }

    public function mount($slug)
    {
        $this->event = Event::published()
            ->withoutGlobalScopes()
            ->where('slug', $slug)
            ->firstOrFail();

        if ($this->event->start_date->endOfDay()->isPast()) {
            abort(403, 'This event has already ended. Tickets can no longer be purchased.');
        }

        // Pre-select first ticket type if available
        if ($this->event->ticketTypes->count() > 0) {
            $this->selectedTicketTypeId = $this->event->ticketTypes->first()->id;
        }

        if (auth()->check()) {
            $this->name  = auth()->user()->name;
            $this->email = auth()->user()->email;
        }
    }

    public function nextStep(TicketReservationService $reservationService)
    {
        $this->validate();

        if ($this->step === 1) {
            $ticketType = TicketType::find($this->selectedTicketTypeId);

            // Validate max per order
            if ($ticketType->max_per_order && $this->quantity > $ticketType->max_per_order) {
                $this->addError('quantity', "Maximum {$ticketType->max_per_order} tickets allowed per order.");
                return;
            }

            // ── RESERVATION: Lock tickets now ───────────────────────────────────
            try {
                $reservation = $reservationService->reserve(
                    $ticketType,
                    $this->quantity,
                    auth()->id(),
                    session()->getId()
                );

                $this->reservationId        = $reservation->id;
                $this->reservationExpiresAt = $reservation->expires_at->toIso8601String();

            } catch (\Exception $e) {
                $this->addError('quantity', $e->getMessage());
                return;
            }
            // ───────────────────────────────────────────────────────────────────
        }

        $this->step++;
    }

    public function prevStep(TicketReservationService $reservationService)
    {
        // Going back to step 1 means releasing the current reservation
        if ($this->step === 2 && $this->reservationId) {
            $reservationService->release($this->reservationId);
            $this->reservationId        = null;
            $this->reservationExpiresAt = null;
        }

        $this->step--;
    }

    public function applyCoupon(\App\Services\CouponService $couponService)
    {
        $this->validate(['couponCode' => 'required']);

        $result = $couponService->validate(
            $this->couponCode,
            $this->event->id,
            auth()->id() ?: 0
        );

        if (!$result['valid']) {
            $this->addError('couponCode', $result['message']);
            return;
        }

        $this->appliedCoupon = $result['coupon'];
        $this->discount      = $couponService->calculateDiscount($this->appliedCoupon, $this->subtotal);

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
        return max(0, $this->subtotal - $this->discount);
    }

    /**
     * How many seconds remain on the current reservation (for the countdown timer).
     */
    public function getReservationSecondsRemainingProperty(): int
    {
        if (!$this->reservationExpiresAt) return 0;
        return max(0, now()->diffInSeconds(\Carbon\Carbon::parse($this->reservationExpiresAt), false));
    }

    public function pay(TicketReservationService $reservationService)
    {
        $this->validate();

        // ── Guard: Ensure the reservation is still active before charging ──────
        if ($this->reservationId) {
            $reservation = TicketReservation::find($this->reservationId);

            if (!$reservation || $reservation->isExpired()) {
                session()->flash('error', 'Your ticket hold has expired. Please start over and select your tickets again.');
                $this->reservationId        = null;
                $this->reservationExpiresAt = null;
                $this->step                 = 1;
                return;
            }
        }
        // ────────────────────────────────────────────────────────────────────────

        \Illuminate\Support\Facades\DB::transaction(function () {
            Stripe::setApiKey(config('services.stripe.secret'));

            $this->currentOrder = Order::create([
                'organizer_id'      => $this->event->organizer_id,
                'event_id'          => $this->event->id,
                'user_id'           => auth()->id(),
                'coupon_id'         => $this->appliedCoupon?->id,
                'order_number'      => 'ORD-' . strtoupper(\Illuminate\Support\Str::random(8)),
                'total_amount'      => $this->total,
                'status'            => 'pending',
                'reservation_id'    => $this->reservationId,
            ]);

            // 2. Create Order Items
            \App\Models\OrderItem::create([
                'order_id'       => $this->currentOrder->id,
                'ticket_type_id' => $this->selectedTicketTypeId,
                'quantity'       => $this->quantity,
                'unit_price'     => $this->selectedTicketType->price,
                'subtotal'       => $this->subtotal,
            ]);
        });

        $order = $this->currentOrder;

        // 3. Create Stripe Session
        $session = Session::create([
            'payment_method_types' => ['card'],
            'line_items'           => [[
                'price_data' => [
                    'currency'     => 'eur',
                    'product_data' => [
                        'name' => "{$this->event->title} - {$this->selectedTicketType->name}",
                    ],
                    'unit_amount'  => $this->total * 100,
                ],
                'quantity'   => 1,
            ]],
            'mode'           => 'payment',
            'success_url'    => route('public.checkout.success', $order->order_number),
            'cancel_url'     => route('public.checkout', $this->event->slug) . '?cancelled=1',
            'customer_email' => $this->email,
            'metadata'       => [
                'order_id'       => $order->id,
                'event_id'       => $this->event->id,
                'ticket_type_id' => $this->selectedTicketTypeId,
                'quantity'       => $this->quantity,
                'reservation_id' => $this->reservationId,
            ],
        ]);

        $order->update(['payment_intent_id' => $session->id]);

        return redirect($session->url);
    }

    #[Layout('layouts.public')]
    public function render()
    {
        return view('livewire.public.checkout');
    }
}
