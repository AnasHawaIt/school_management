<?php

namespace Modules\Transport\Listeners\RouteListeners;

use Modules\Transport\Events\RouteEvents\RouteCreated;

class RouteCreatedLogEventListener
{
    public function handle(RouteCreated $event)
    {
        $route = $event->route;

        activity()
            ->causedBy($event->userId)
            ->performedOn($route)
            ->withProperties([
                'Route_id' => $route->id,
            ])
            ->log('Route.created');
    }
}
