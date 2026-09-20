<?php

namespace Modules\Transport\app\Events\RouteStopEvents;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Modules\Transport\app\Entities\RouteStop;

class RouteStopUpdated
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public RouteStop $routeStop,
        public ?int $userId = null,
        public array $changes = []
    ) {}
}

