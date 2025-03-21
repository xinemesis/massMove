<?php

namespace App\Events;

use App\Models\CarAuction;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;

class NewAuctionCreated implements ShouldBroadcastNow
{
    use InteractsWithSockets, SerializesModels;

    public $auction;

    public function __construct(CarAuction $auction)
    {
        $this->auction = $auction;
    }

    public function broadcastOn()
    {
        return new Channel('auctions');
    }

    public function broadcastWith()
    {
        return [
            'id' => $this->auction->id,
            'name' => $this->auction->name,
            'current_bid' => $this->auction->current_bid,
            'end_time' => $this->auction->end_time
        ];
    }
}
