<?php

namespace App\Livewire\Public;

use Livewire\Component;
use App\Mail\SupportContactMail;
use Illuminate\Support\Facades\Mail;

class SupportForm extends Component
{
    public $name = '';
    public $email = '';
    public $subject = '';
    public $message = '';
    public $success = false;

    public function mount()
    {
        if (auth()->check()) {
            $this->name = auth()->user()->name;
            $this->email = auth()->user()->email;
        }
    }

    protected $rules = [
        'name' => 'required|string|max:255',
        'email' => 'required|email|max:255',
        'subject' => 'required|string|max:255',
        'message' => 'required|string|max:5000',
    ];

    public function submit()
    {
        $this->validate();

        try {
            Mail::to('support@tickethub.com')->send(new SupportContactMail([
                'name' => $this->name,
                'email' => $this->email,
                'subject' => $this->subject,
                'message' => $this->message,
            ]));

            $this->success = true;
            $this->reset(['subject', 'message']);
            
            if (!auth()->check()) {
                $this->reset(['name', 'email']);
            }
        } catch (\Exception $e) {
            \Log::error('Failed to send support email: ' . $e->getMessage());
            $this->addError('general', 'We encountered an error while trying to send your message. Please try again later.');
        }
    }

    public function render()
    {
        return view('livewire.public.support-form');
    }
}
