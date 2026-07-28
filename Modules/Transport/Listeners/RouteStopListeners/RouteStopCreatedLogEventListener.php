<?php

namespace Modules\Transport\Listeners\RouteStopListeners;

use Modules\Transport\Events\RouteStopEvents\RouteStopCreated;

class RouteStopCreatedLogEventListener
{
    public function handle(RouteStopCreated $event)
    {
        $routeStop = $event->routeStop;

        activity()
            ->causedBy($event->userId)
            ->performedOn($routeStop)
            ->withProperties([
                'RouteStop_id' => $routeStop->id,
            ])
            ->log('RouteStop.created');
    }
}
