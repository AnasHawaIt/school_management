<?php

namespace Modules\Transport\Listeners\BusListeners\BusUpdateListeners;

use Modules\Transport\Entities\EventLog;
use Modules\Transport\Events\BusEvents\BusUpdated;

class BusUpdateLogEventListener
{
    public function handle(BusUpdated $event)
    {
        EventLog::create([
            'user_id' => $event->userId,
            'event_type' => 'BusUpdated',
            'data' =>$event->bus
        ]);
    }
}
