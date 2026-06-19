<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
// 1. UBAH BARIS INI: Ganti ShouldBroadcast menjadi ShouldBroadcastNow
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow; 
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

// 2. UBAH BARIS INI: Gunakan ShouldBroadcastNow
class MoneyDeposited implements ShouldBroadcastNow 
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $package;
    public $deposit;

    public function __construct($package, $deposit)
    {
        $this->package = $package;
        $this->deposit = $deposit;
    }

    public function broadcastOn(): Channel
    {
        return new PrivateChannel('package.' . $this->package->id);
    }

    public function broadcastAs(): string
    {
        return 'money-deposited';
    }
}