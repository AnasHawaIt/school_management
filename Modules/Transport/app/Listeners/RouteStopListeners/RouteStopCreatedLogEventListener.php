<?php

namespace Modules\Transport\app\Listeners\RouteStopListeners;

use Modules\Transport\app\Events\RouteStopEvents\RouteStopCreated;

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
