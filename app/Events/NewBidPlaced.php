<?php

namespace App\Events;

use App\Models\CarAuction;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NewBidPlaced implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $auction;

    public function __construct(CarAuction $auction)
    {
        $this->auction = $auction;
    }

    public function broadcastOn()
    {
        return new Channel('auctions'); // Canal donde se enviarán los eventos
    }

    public function broadcastWith()
    {
        return [
            'id' => $this->auction->id,
            'current_bid' => $this->auction->current_bid
        ];
    }
}
