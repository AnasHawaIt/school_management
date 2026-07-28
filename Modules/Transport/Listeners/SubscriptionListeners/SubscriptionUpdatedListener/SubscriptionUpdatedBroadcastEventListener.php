<?php


namespace Modules\Transport\Listeners\SubscriptionListeners\SubscriptionUpdatedListener;


use Modules\Transport\Events\Broadcasts\SubscriptionBroadcast;
use Modules\Transport\Events\SubscriptionEvents\SubscriptionUpdated;

class SubscriptionUpdatedBroadcastEventListener
{

    public function handle(SubscriptionUpdated $event)
    {
        broadcast(new SubscriptionBroadcast($event->subscription))->toOthers();
    }
}
