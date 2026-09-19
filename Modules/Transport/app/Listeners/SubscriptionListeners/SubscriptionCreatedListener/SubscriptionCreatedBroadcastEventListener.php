<?php


namespace Modules\Transport\app\Listeners\SubscriptionListeners\SubscriptionCreatedListener;


use Modules\Transport\app\Events\Broadcasts\SubscriptionBroadcast;
use Modules\Transport\app\Events\SubscriptionEvents\SubscriptionCreated;

class SubscriptionCreatedBroadcastEventListener
{

    public function handle(SubscriptionCreated $event)
    {
        broadcast(new SubscriptionBroadcast($event->subscription))->toOthers();
    }
}
