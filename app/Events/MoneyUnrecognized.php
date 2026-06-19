<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow; 
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MoneyUnrecognized implements ShouldBroadcastNow 
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $package;

    public function __construct($package)
    {
        $this->package = $package;
    }

    public function broadcastOn(): Channel
    {
        // Channel yang sama dengan milik Anda
        return new PrivateChannel('package.' . $this->package->id);
    }

    public function broadcastAs(): string
    {
        return 'money-unrecognized';
    }
}