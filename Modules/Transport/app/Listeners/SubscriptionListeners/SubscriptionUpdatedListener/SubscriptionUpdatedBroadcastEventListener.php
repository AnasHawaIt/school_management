<?php


namespace Modules\Transport\app\Listeners\SubscriptionListeners\SubscriptionUpdatedListener;


use Modules\Transport\app\Events\Broadcasts\SubscriptionBroadcast;
use Modules\Transport\app\Events\SubscriptionEvents\SubscriptionUpdated;

class SubscriptionUpdatedBroadcastEventListener
{

    public function handle(SubscriptionUpdated $event)
    {
        broadcast(new SubscriptionBroadcast($event->subscription))->toOthers();
    }
}
