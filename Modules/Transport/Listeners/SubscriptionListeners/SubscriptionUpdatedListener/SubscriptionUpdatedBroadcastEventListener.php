<?php


namespace Modules\Transport\Listeners\SubscriptionListeners\SubscriptionUpdatedListener;


use Modules\Transport\Events\Broadcasts\SubscriptionBroadcast;
use Modules\Transport\Events\SubscriptionEvents\TransactionUpdated;

class SubscriptionUpdatedBroadcastEventListener
{

    public function handle(TransactionUpdated $event)
    {
        broadcast(new SubscriptionBroadcast($event->subscription))->toOthers();
    }
}
