<?php

namespace Modules\Transport\app\Events\Broadcasts;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SubscriptionBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $subscription;

    public function __construct($subscription)
    {
        $this->subscription = $subscription;
    }

    public function broadcastOn()
    {
        return new Channel('subscriptions');
    }

}
