<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class RearDoorStatusUpdated implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $userId;
    public $status; 

    public function __construct($userId, $status)
    {
        $this->userId = $userId;
        $this->status = $status; // Data 'open' / 'closed' dari MQTT masuk ke sini
    }

    public function broadcastOn(): Channel
    {
        // KITA GUNAKAN PrivateChannel DI SINI
        return new PrivateChannel('user.' . $this->userId); 
    }

    public function broadcastAs(): string
    {
        return 'RearDoorStatusUpdated'; 
    }
}