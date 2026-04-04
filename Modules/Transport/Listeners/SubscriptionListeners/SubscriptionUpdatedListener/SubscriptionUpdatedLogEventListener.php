<?php

namespace Modules\Transport\Listeners\SubscriptionListeners\SubscriptionUpdatedListener;


use Modules\Transport\Entities\EventLog;
use Modules\Transport\Events\SubscriptionEvents\SubscriptionUpdated;

class SubscriptionUpdatedLogEventListener
{
    public function handle(SubscriptionUpdated $event)
    {
        EventLog::create([
            'user_id' => $event->userId,
            'event_type' => 'SubscriptionUpdated',
            'data' =>$event->subscription,
        ]);
    }
}
