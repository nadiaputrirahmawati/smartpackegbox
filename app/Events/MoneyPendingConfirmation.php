<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow; 
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MoneyPendingConfirmation implements ShouldBroadcastNow 
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $package;
    public $nominal;
    public $color_signature;

    public function __construct($package, $nominal, $color_signature)
    {
        $this->package = $package;
        $this->nominal = $nominal;
        $this->color_signature = $color_signature;
    }

    public function broadcastOn(): Channel
    {
        // Channel yang sama dengan milik Anda
        return new PrivateChannel('package.' . $this->package->id);
    }

    public function broadcastAs(): string
    {
        return 'money-pending-confirmation';
    }
}