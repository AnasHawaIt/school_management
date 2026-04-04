<?php

namespace Modules\Transport\Listeners\BusListeners\BusCreatedListeners;

use Modules\Transport\Entities\EventLog;
use Modules\Transport\Events\BusEvents\BusCreated;

class BusCreatedLogEventListener
{
    public function handle(BusCreated $event)
    {
        EventLog::create([
            'user_id' => $event->userId,
            'event_type' => 'BusCreated',
            'data' =>$event->bus
        ]);
    }
}
