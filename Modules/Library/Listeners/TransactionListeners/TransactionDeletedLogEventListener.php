<?php

namespace Modules\Library\Listeners\TransactionListeners;



use Modules\Library\Entities\EventLog;
use Modules\Library\Events\TransactionEvents\TransactionDeleted;

class TransactionDeletedLogEventListener
{
    public function handle(TransactionDeleted $event)
    {
        EventLog::create([
            'user_id' => $event->userId,
            'event_type' => 'MemberDeleted',
            'data' =>$event->transaction
        ]);
    }
}
