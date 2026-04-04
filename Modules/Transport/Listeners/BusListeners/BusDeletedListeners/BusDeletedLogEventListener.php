<?php

namespace Modules\Transport\Listeners\BusListeners\BusDeletedListeners;

use Modules\Transport\Entities\EventLog;
use Modules\Transport\Events\BusEvents\BusDeleted;

class BusDeletedLogEventListener
{
    public function handle(BusDeleted $event)
    {
        EventLog::create([
            'user_id' => $event->userId,
            'event_type' => 'BusDeleted',
            'data' =>$event->bus
        ]);
    }
}
