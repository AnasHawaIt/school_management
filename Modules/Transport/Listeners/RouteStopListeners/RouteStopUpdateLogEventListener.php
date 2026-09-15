<?php

namespace Modules\Transport\Listeners\RouteStopListeners;

use Modules\Transport\Events\RouteStopEvents\RouteStopUpdated;

class RouteStopUpdateLogEventListener
{
    public function handle(RouteStopUpdated $event)
    {
        $routeStop = $event->routeStop;

        activity()
            ->causedBy(auth()->user())
            ->performedOn($routeStop)
            ->withProperties([
                'RouteStop_id' => $routeStop->id,
            ])
            ->log('RouteStop.updated');
    }
}
