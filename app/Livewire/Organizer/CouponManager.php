<?php

namespace App\Livewire\Organizer;

use App\Models\Coupon;
use App\Models\Event;
use Livewire\Component;
use Livewire\WithPagination;

class CouponManager extends Component
{
    use WithPagination;

    public $showModal = false;
    public $isEditing = false;
    
    // Form fields
    public $couponId;
    public $code;
    public $type = 'percentage';
    public $value;
    public $event_id = null;
    public $expires_at;
    public $max_usages;
    public $once_per_customer = false;

    protected function rules()
    {
        return [
            'code' => 'required|string|max:50|unique:coupons,code,' . $this->couponId,
            'type' => 'required|in:percentage,fixed',
            'value' => 'required|numeric|min:0',
            'event_id' => 'nullable|exists:events,id',
            'expires_at' => 'nullable|date|after:today',
            'max_usages' => 'nullable|integer|min:1',
            'once_per_customer' => 'boolean',
        ];
    }

    public function createCoupon()
    {
        $this->resetValidation();
        $this->reset(['couponId', 'code', 'type', 'value', 'event_id', 'expires_at', 'max_usages', 'once_per_customer']);
        $this->isEditing = false;
        $this->showModal = true;
    }

    public function editCoupon($id)
    {
        $this->resetValidation();
        
        $coupon = Coupon::where('organizer_id', auth()->user()->organizer->id)->findOrFail($id);
        $this->couponId = $coupon->id;
        $this->code = $coupon->code;
        $this->type = $coupon->type;
        $this->value = $coupon->value;
        $this->event_id = $coupon->event_id;
        $this->expires_at = $coupon->expires_at ? $coupon->expires_at->format('Y-m-d') : null;
        $this->max_usages = $coupon->max_usages;
        $this->once_per_customer = $coupon->once_per_customer;
        
        $this->isEditing = true;
        $this->showModal = true;
    }

    public function saveCoupon()
    {
        $this->validate();

        $data = [
            'organizer_id' => auth()->user()->organizer->id,
            'code' => strtoupper($this->code),
            'type' => $this->type,
            'value' => $this->value,
            'event_id' => $this->event_id ?: null,
            'expires_at' => $this->expires_at ?: null,
            'max_usages' => $this->max_usages ?: null,
            'once_per_customer' => $this->once_per_customer,
        ];

        if ($this->isEditing) {
            $coupon = Coupon::where('organizer_id', auth()->user()->organizer->id)->findOrFail($this->couponId);
            $coupon->update($data);
            session()->flash('success', 'Coupon updated successfully.');
        } else {
            Coupon::create($data);
            session()->flash('success', 'Coupon created successfully.');
        }

        $this->showModal = false;
    }

    public function deleteCoupon($id)
    {
        $coupon = Coupon::where('organizer_id', auth()->user()->organizer->id)->findOrFail($id);
        $coupon->delete();
        session()->flash('success', 'Coupon deleted.');
    }

    public function render()
    {
        $coupons = Coupon::where('organizer_id', auth()->user()->organizer->id)
            ->with('event')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $events = Event::where('organizer_id', auth()->user()->organizer->id)
            ->orderBy('title')
            ->get();

        return view('livewire.organizer.coupon-manager', [
            'coupons' => $coupons,
            'events' => $events
        ])->layout('layouts.app');
    }
}
