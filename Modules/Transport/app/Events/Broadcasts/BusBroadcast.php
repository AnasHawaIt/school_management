<?php

namespace Modules\Transport\app\Events\Broadcasts;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class BusBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $bus;

    public function __construct($bus)
    {
        $this->bus = $bus;
    }

    public function broadcastOn()
    {
        return new Channel('buses');
    }

}
