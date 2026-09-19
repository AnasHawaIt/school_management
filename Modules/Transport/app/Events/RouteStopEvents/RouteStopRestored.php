<?php


namespace Modules\Transport\app\Events\RouteStopEvents;


use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Modules\Transport\app\Entities\RouteStop;

class RouteStopRestored
{
    use Dispatchable, SerializesModels;

    public RouteStop $routeStop;

    public function __construct(RouteStop $routeStop)
    {
        $this->routeStop = $routeStop;
    }
}
