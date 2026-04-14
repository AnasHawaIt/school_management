<?php

namespace Modules\Transport\Listeners\SubscriptionListeners\SubscriptionDeletedListener;


use Modules\Transport\Entities\EventLog;
use Modules\Transport\Events\SubscriptionEvents\TransactionDeleted;

class SubscriptionDeletedLogEventListener
{
    public function handle(TransactionDeleted $event)
    {
        EventLog::create([
            'user_id' => $event->userId,
            'event_type' => 'TransactionDeleted',
            'data' =>$event->subscription,
        ]);
    }
}
