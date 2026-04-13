<?php

namespace Modules\Library\Listeners\TransactionListeners;

use Modules\Library\Entities\EventLog;
use Modules\Library\Events\TransactionEvents\TransactionCreated;
class TransactionCreatedLogEventListener
{
    public function handle(TransactionCreated $event)
    {
        EventLog::create([
            'user_id' => $event->userId,
            'event_type' => 'MemberCreated',
            'data' => $event->transaction,
            ]);
    }
}
