<?php

namespace Modules\Transport\Listeners\RouteListeners;

use Modules\Transport\Entities\EventLog;
use Modules\Transport\Events\RouteEvents\RouteCreated;
use Modules\Transport\Events\RouteEvents\AuthorUpdated;

class RouteUpdatedLogEventListener
{
    public function handle(AuthorUpdated $event)
    {
        EventLog::create([
            'user_id' => $event->userId,
            'event_type' => 'RouteCreated',
            'data' =>$event->route
        ]);
    }
}
