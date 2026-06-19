<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class OrderConfirmed extends Notification
{
    use Queueable;

    public $order;

    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        return [
            'order_id' => $this->order->id,
            'order_number' => $this->order->order_number,
            'title' => 'Order Confirmed!',
            'message' => "Your order #{$this->order->order_number} for {$this->order->event->title} has been confirmed.",
            'url' => route('public.tickets.index'),
            'icon' => 'shopping_bag',
        ];
    }
}
