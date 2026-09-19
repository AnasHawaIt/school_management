<?php

namespace Modules\Transport\app\Listeners\RouteListeners;

use Modules\Transport\app\Events\RouteEvents\RouteUpdated;

class RouteUpdatedLogEventListener
{
    public function handle(RouteUpdated $event)
    {
        $route = $event->route;

        activity()
            ->causedBy($event->userId)
            ->performedOn($route)
            ->withProperties([
                'Route_id' => $route->id,
            ])
            ->log('Route.Updated');
    }
}
