<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;

class NewBidNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $auction;

    public function __construct($auction)
    {
        $this->auction = $auction;
    }

    public function via($notifiable)
    {
        return ['broadcast']; // Enviar en tiempo real con Pusher
    }

    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage([
            'message' => 'Nueva puja en ' . $this->auction->name . ': $' . $this->auction->current_bid
        ]);
    }
}
