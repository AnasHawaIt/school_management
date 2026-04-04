<?php

namespace Modules\Transport\Listeners\SubscriptionListeners\SubscriptionDeletedListener;


use Modules\Transport\Entities\EventLog;
use Modules\Transport\Events\SubscriptionEvents\SubscriptionDeleted;

class SubscriptionDeletedLogEventListener
{
    public function handle(SubscriptionDeleted $event)
    {
        EventLog::create([
            'user_id' => $event->userId,
            'event_type' => 'SubscriptionDeleted',
            'data' =>$event->subscription,
        ]);
    }
}
