<?php


namespace Modules\Transport\Events\RouteEvents;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Modules\Transport\Entities\Route;

class RouteRestored
{
    use Dispatchable, SerializesModels;

    public Route $route;

    public function __construct(Route $route)
    {
        $this->route = $route;
    }
}
