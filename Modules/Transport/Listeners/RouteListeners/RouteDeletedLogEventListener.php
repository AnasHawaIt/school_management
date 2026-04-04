<?php

namespace Modules\Transport\Listeners\RouteListeners;

use Modules\Transport\Entities\EventLog;
use Modules\Transport\Events\RouteEvents\RouteDeleted;

class RouteDeletedLogEventListener
{
    public function handle(RouteDeleted $event)
    {
        EventLog::create([
            'user_id' => $event->userId,
            'event_type' => 'RouteDeleted',
            'data' =>$event->route
        ]);
    }
}
