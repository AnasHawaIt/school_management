<?php

namespace Modules\Transport\Listeners\RouteStopListeners;

use Modules\Transport\Events\RouteStopEvents\RouteStopDeleted;

class RouteStopDeletedLogEventListener
{
    public function handle(RouteStopDeleted $event)
    {
        $routeStop = $event->routeStop;

        activity()
            ->causedBy($event->userId)
            ->performedOn($routeStop)
            ->withProperties([
                'RouteStop_id' => $routeStop->id,
            ])
            ->log('RouteStop.Deleted');
    }
}
