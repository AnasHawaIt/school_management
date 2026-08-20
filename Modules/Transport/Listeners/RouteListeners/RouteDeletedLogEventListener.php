<?php

namespace Modules\Transport\Listeners\RouteListeners;

use Modules\Transport\Events\RouteEvents\RouteDeleted;

class RouteDeletedLogEventListener
{
    public function handle(RouteDeleted $event)
    {
        $route = $event->route;

        activity()
            ->causedBy($event->userId)
            ->performedOn($route)
            ->withProperties([
                'Route_id' => $route->id,
            ])
            ->log('Route.Deleted');
    }
}
