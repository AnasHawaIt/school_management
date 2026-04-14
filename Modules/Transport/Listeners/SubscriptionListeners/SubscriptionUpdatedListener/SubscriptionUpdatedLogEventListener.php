<?php

namespace Modules\Transport\Listeners\SubscriptionListeners\SubscriptionUpdatedListener;


use Modules\Transport\Entities\EventLog;
use Modules\Transport\Events\SubscriptionEvents\TransactionUpdated;

class SubscriptionUpdatedLogEventListener
{
    public function handle(TransactionUpdated $event)
    {
        EventLog::create([
            'user_id' => $event->userId,
            'event_type' => 'TransactionUpdated',
            'data' =>$event->subscription,
        ]);
    }
}
