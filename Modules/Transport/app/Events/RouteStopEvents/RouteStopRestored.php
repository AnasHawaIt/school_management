<?php


namespace Modules\Transport\Events\RouteStopEvents;


use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Modules\Transport\Entities\RouteStop;

class RouteStopRestored
{
    use Dispatchable, SerializesModels;

    public RouteStop $routeStop;

    public function __construct(RouteStop $routeStop)
    {
        $this->routeStop = $routeStop;
    }
}
