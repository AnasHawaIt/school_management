<?php

namespace Modules\Transport\Listeners\RouteStopListeners;

use Modules\Transport\Entities\EventLog;
use Modules\Transport\Events\RouteStopEvents\MemberDeleted;

class RouteStopDeletedLogEventListener
{
    public function handle(MemberDeleted $event)
    {
        EventLog::create([
            'user_id' => $event->userId,
            'event_type' => 'MemberDeleted',
            'data' =>$event->routeStop
        ]);
    }
}
