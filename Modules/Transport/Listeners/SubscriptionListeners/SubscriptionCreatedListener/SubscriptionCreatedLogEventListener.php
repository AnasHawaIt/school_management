<?php

namespace Modules\Transport\Listeners\SubscriptionListeners\SubscriptionCreatedListener;

use Modules\Transport\Entities\EventLog;
use Modules\Transport\Events\SubscriptionEvents\SubscriptionCreated;

class SubscriptionCreatedLogEventListener
{
    public function handle(SubscriptionCreated $event)
    {
        EventLog::create([
            'user_id' => $event->userId,
            'event_type' => 'subscription_created',
            'data' =>$event->subscription,
        ]);
    }
}
