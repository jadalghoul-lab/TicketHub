<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\PayoutRequest;

class PayoutManager extends Component
{
    use WithPagination;

    public $search = '';
    public $statusFilter = '';

    public function updated($property)
    {
        if (in_array($property, ['search', 'statusFilter'])) {
            $this->resetPage();
        }
    }

    public function updateStatus($id, $newStatus)
    {
        $payout = PayoutRequest::findOrFail($id);
        
        if (in_array($newStatus, ['approved', 'paid', 'rejected'])) {
            $payout->update(['status' => $newStatus]);
            session()->flash('success', "Payout request #{$id} marked as {$newStatus}.");
        }
    }

    public function render()
    {
        $query = PayoutRequest::with('organizer.user')
            ->orderBy('created_at', 'desc');

        if ($this->statusFilter) {
            $query->where('status', $this->statusFilter);
        }

        if ($this->search) {
            $query->whereHas('organizer.user', function ($q) {
                $q->where('name', 'like', "%{$this->search}%")
                  ->orWhere('email', 'like', "%{$this->search}%");
            });
        }

        return view('livewire.admin.payout-manager', [
            'payouts' => $query->paginate(15)
        ])->layout('layouts.app');
    }
}
