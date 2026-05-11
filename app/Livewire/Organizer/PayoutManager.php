<?php

namespace App\Livewire\Organizer;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\PayoutRequest;
use App\Services\PayoutService;

class PayoutManager extends Component
{
    use WithPagination;

    public $amount;
    public $bank_details;
    public $showModal = false;

    protected function rules()
    {
        return [
            'amount' => 'required|numeric|min:1',
            'bank_details' => 'required|string|min:10',
        ];
    }

    public function createRequest()
    {
        $this->reset(['amount', 'bank_details']);
        $this->resetValidation();
        $this->showModal = true;
    }

    public function submitRequest(PayoutService $payoutService)
    {
        $this->validate();

        $organizer = auth()->user()->organizer;
        $availableBalance = $payoutService->getAvailableBalance($organizer->id);

        if ($this->amount > $availableBalance) {
            $this->addError('amount', 'You cannot request more than your available balance.');
            return;
        }

        $result = $payoutService->requestPayout($organizer, $this->amount, $this->bank_details);

        if ($result['success']) {
            session()->flash('success', $result['message']);
            $this->showModal = false;
        } else {
            session()->flash('error', $result['message']);
        }
    }

    public function render(PayoutService $payoutService)
    {
        $organizer = auth()->user()->organizer;
        $availableBalance = $payoutService->getAvailableBalance($organizer->id);
        
        $payouts = PayoutRequest::where('organizer_id', $organizer->id)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('livewire.organizer.payout-manager', [
            'availableBalance' => $availableBalance,
            'payouts' => $payouts,
        ])->layout('layouts.app');
    }
}
