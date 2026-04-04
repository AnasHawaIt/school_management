<?php

namespace Modules\Transport\Listeners\RouteStopListeners;

use Modules\Transport\Entities\EventLog;
use Modules\Transport\Events\RouteStopEvents\RouteStopCreated;

class RouteStopCreatedLogEventListener
{
    public function handle(RouteStopCreated $event)
    {
        EventLog::create([
            'user_id' => $event->userId,
            'event_type' => 'RouteStopCreated',
            'data' =>$event->routeStop,
        ]);
    }
}
