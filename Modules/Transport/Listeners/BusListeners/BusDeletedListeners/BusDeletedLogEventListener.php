<?php

namespace Modules\Transport\Listeners\BusListeners\BusDeletedListeners;

use Modules\Transport\Entities\EventLog;
use Modules\Transport\Events\BusEvents\CategoryDeleted;

class BusDeletedLogEventListener
{
    public function handle(CategoryDeleted $event)
    {
        EventLog::create([
            'user_id' => $event->userId,
            'event_type' => 'CategoryDeleted',
            'data' =>$event->bus
        ]);
    }
}
