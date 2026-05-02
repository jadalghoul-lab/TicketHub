<?php

namespace App\Livewire\Public;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class NotificationBell extends Component
{
    public $unreadCount = 0;
    public $showDropdown = false;

    public function mount()
    {
        $this->updateCount();
    }

    public function updateCount()
    {
        if (Auth::check()) {
            $this->unreadCount = Auth::user()->unreadNotifications()->count();
        }
    }

    public function markAllAsRead()
    {
        if (Auth::check()) {
            Auth::user()->unreadNotifications->markAsRead();
            $this->updateCount();
        }
    }

    public function render()
    {
        $notifications = Auth::check() 
            ? Auth::user()->notifications()->latest()->limit(5)->get() 
            : collect();

        return view('livewire.public.notification-bell', [
            'notifications' => $notifications
        ]);
    }
}
