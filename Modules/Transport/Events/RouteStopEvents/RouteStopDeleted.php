<?php

namespace Modules\Transport\Events\RouteStopEvents;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Modules\Announcement\Entities\Announcement;
use Modules\Transport\Entities\RouteStop;

class RouteStopDeleted
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public RouteStop $routeStop,
        public ?int $userId = null,
        public ?string $socketId = null
    ) {}
}


