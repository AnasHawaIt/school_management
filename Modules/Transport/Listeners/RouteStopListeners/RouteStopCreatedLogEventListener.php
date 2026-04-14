<?php

namespace Modules\Transport\Listeners\RouteStopListeners;

use Modules\Transport\Entities\EventLog;
use Modules\Transport\Events\RouteStopEvents\MemberCreated;

class RouteStopCreatedLogEventListener
{
    public function handle(MemberCreated $event)
    {
        EventLog::create([
            'user_id' => $event->userId,
            'event_type' => 'MemberCreated',
            'data' =>$event->routeStop,
        ]);
    }
}
