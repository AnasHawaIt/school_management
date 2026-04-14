<?php

namespace Modules\Transport\Listeners\RouteStopListeners;

use Modules\Transport\Entities\EventLog;
use Modules\Transport\Events\RouteStopEvents\MemberUpdated;

class RouteStopUpdateLogEventListener
{
    public function handle(MemberUpdated $event)
    {
        EventLog::create([
            'user_id' => $event->userId,
            'event_type' => 'MemberUpdated',
            'data' =>$event->routeStop
        ]);
    }
}
