<?php

namespace Modules\Transport\Listeners\RouteListeners;

use Modules\Transport\Entities\EventLog;
use Modules\Transport\Events\RouteEvents\RouteCreated;
use Modules\Transport\Events\RouteEvents\RouteUpdated;

class RouteUpdatedLogEventListener
{
    public function handle(RouteUpdated $event)
    {
        EventLog::create([
            'user_id' => $event->userId,
            'event_type' => 'RouteCreated',
            'data' =>$event->route
        ]);
    }
}
