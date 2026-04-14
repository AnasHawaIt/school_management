<?php

namespace Modules\Transport\Listeners\RouteListeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Transport\Entities\EventLog;
use Modules\Transport\Events\RouteEvents\RouteCreated;

class RouteCreatedLogEventListener
{
    public function handle(RouteCreated $event)
    {
        EventLog::create([
            'user_id' => $event->userId,
            'event_type' => 'RouteCreated',
            'data' =>$event->route
        ]);
    }
}
