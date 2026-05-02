<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Services\OrganizerService;

class OrganizerProfile extends Component
{
    use WithFileUploads;

    public $company_name = '';
    public $contact_email = '';
    public $payout_email = '';
    public $logo; // new upload
    public $current_logo = null;

    public function mount()
    {
        $organizer = auth()->user()->organizer;
        if ($organizer) {
            $this->company_name = $organizer->company_name;
            $this->contact_email = $organizer->contact_email;
            $this->payout_email = $organizer->payout_email;
            $this->current_logo = $organizer->logo;
        }
    }

    protected $rules = [
        'company_name' => 'required|string|max:255',
        'contact_email' => 'nullable|email|max:255',
        'payout_email' => 'nullable|email|max:255',
        'logo' => 'nullable|image|max:2048', // 2MB Max
    ];

    public function save(OrganizerService $organizerService)
    {
        $this->validate();

        $organizer = $organizerService->saveProfile(
            [
                'company_name' => $this->company_name,
                'contact_email' => $this->contact_email,
                'payout_email' => $this->payout_email,
            ],
            $this->logo
        );

        $this->current_logo = $organizer->logo;
        $this->logo = null;

        session()->flash('message', 'Profile saved successfully.');
    }

    public function render()
    {
        return view('livewire.organizer-profile');
    }
}
