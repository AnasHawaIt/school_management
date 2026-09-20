<?php

namespace Modules\Transport\app\Events\Broadcasts;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class RouteBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $route;

    public function __construct($route)
    {
        $this->route = $route;
    }

    public function broadcastOn()
    {
        return new Channel('routes');
    }

}
