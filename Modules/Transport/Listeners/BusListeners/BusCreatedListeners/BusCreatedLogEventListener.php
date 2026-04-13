<?php

namespace Modules\Transport\Listeners\BusListeners\BusCreatedListeners;

use Modules\Transport\Entities\EventLog;
use Modules\Transport\Events\BusEvents\CategoryCreated;

class BusCreatedLogEventListener
{
    public function handle(CategoryCreated $event)
    {
        EventLog::create([
            'user_id' => $event->userId,
            'event_type' => 'CategoryCreated',
            'data' =>$event->bus
        ]);
    }
}
