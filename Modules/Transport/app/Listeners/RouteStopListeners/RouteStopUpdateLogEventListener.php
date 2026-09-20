<?php

namespace Modules\Transport\app\Listeners\RouteStopListeners;

use Modules\Transport\app\Events\RouteStopEvents\RouteStopUpdated;

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
