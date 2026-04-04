<?php

namespace Modules\Transport\Listeners\RouteStopListeners;

use Modules\Transport\Entities\EventLog;
use Modules\Transport\Events\RouteStopEvents\RouteStopUpdated;

class RouteStopUpdateLogEventListener
{
    public function handle(RouteStopUpdated $event)
    {
        EventLog::create([
            'user_id' => $event->userId,
            'event_type' => 'RouteStopUpdated',
            'data' =>$event->routeStop
        ]);
    }
}
