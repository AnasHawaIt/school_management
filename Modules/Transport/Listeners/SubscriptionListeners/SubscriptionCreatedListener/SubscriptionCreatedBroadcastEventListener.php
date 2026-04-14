<?php


namespace Modules\Transport\Listeners\SubscriptionListeners\SubscriptionCreatedListener;


use Modules\Transport\Events\Broadcasts\SubscriptionBroadcast;
use Modules\Transport\Events\SubscriptionEvents\TransactionUpdated;

class SubscriptionCreatedBroadcastEventListener
{

    public function handle(TransactionUpdated $event)
    {
        broadcast(new SubscriptionBroadcast($event->subscription))->toOthers();
    }
}
