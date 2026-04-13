<?php

namespace Modules\Library\Listeners\TransactionListeners;


use Modules\Library\Entities\EventLog;
use Modules\Library\Events\TransactionEvents\TransactionUpdated;

class TransactionUpdateLogEventListener
{
    public function handle(TransactionUpdated $event)
    {
        EventLog::create([
            'user_id' => $event->userId,
            'event_type' => 'MemberUpdated',
            'data' =>$event->transaction
        ]);
    }
}
