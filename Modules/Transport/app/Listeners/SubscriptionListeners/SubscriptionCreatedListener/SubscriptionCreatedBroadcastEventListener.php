<?php


namespace Modules\Transport\Listeners\SubscriptionListeners\SubscriptionCreatedListener;


use Modules\Transport\Events\Broadcasts\SubscriptionBroadcast;
use Modules\Transport\Events\SubscriptionEvents\SubscriptionCreated;

class SubscriptionCreatedBroadcastEventListener
{

    public function handle(SubscriptionCreated $event)
    {
        broadcast(new SubscriptionBroadcast($event->subscription))->toOthers();
    }
}
