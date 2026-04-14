<?php

namespace Modules\Transport\Listeners\BusListeners\BusUpdateListeners;

use Modules\Transport\Entities\EventLog;
use Modules\Transport\Events\BusEvents\CategoryUpdated;

class BusUpdateLogEventListener
{
    public function handle(CategoryUpdated $event)
    {
        EventLog::create([
            'user_id' => $event->userId,
            'event_type' => 'CategoryUpdated',
            'data' =>$event->bus
        ]);
    }
}
