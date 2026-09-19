<?php

namespace Modules\Transport\app\Events\RouteStopEvents;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Modules\Transport\app\Entities\RouteStop;

class RouteStopCreated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public RouteStop $routeStop,
        public ?int $userId = null,
        public ?string $socketId = null
    ) {}

}

