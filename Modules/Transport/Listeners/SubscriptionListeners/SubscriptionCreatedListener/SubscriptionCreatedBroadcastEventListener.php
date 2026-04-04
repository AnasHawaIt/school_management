<?php


namespace Modules\Transport\Listeners\SubscriptionListeners\SubscriptionCreatedListener;


use Modules\Transport\Events\Broadcasts\SubscriptionBroadcast;
use Modules\Transport\Events\SubscriptionEvents\SubscriptionUpdated;

class SubscriptionCreatedBroadcastEventListener
{

    public function handle(SubscriptionUpdated $event)
    {
        broadcast(new SubscriptionBroadcast($event->subscription))->toOthers();
    }
}
