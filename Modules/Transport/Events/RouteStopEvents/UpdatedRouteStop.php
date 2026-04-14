<?php

namespace Modules\Transport\Events\RouteStopEvents;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Modules\Announcement\Entities\Announcement;
use Modules\Transport\Entities\RouteStop;

class UpdatedRouteStop
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public RouteStop $routeStop,
        // public array $phones,
        public $users
    ) {}
}

