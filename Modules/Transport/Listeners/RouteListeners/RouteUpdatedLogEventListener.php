<?php

namespace Modules\Transport\Listeners\RouteListeners;

use Modules\Transport\Events\RouteEvents\RouteUpdated;

class RouteUpdatedLogEventListener
{
    public function handle(RouteUpdated $event)
    {
        $route = $event->route;

        activity()
            ->causedBy(auth()->user())
            ->performedOn($route)
            ->withProperties([
                'Route_id' => $route->id,
            ])
            ->log('Route.Updated');
    }
}
