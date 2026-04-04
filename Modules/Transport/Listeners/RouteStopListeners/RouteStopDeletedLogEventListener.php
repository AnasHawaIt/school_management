<?php

namespace Modules\Transport\Listeners\RouteStopListeners;

use Modules\Transport\Entities\EventLog;
use Modules\Transport\Events\RouteStopEvents\RouteStopDeleted;

class RouteStopDeletedLogEventListener
{
    public function handle(RouteStopDeleted $event)
    {
        EventLog::create([
            'user_id' => $event->userId,
            'event_type' => 'RouteStopDeleted',
            'data' =>$event->routeStop
        ]);
    }
}
