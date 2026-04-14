<?php

namespace Modules\Transport\Events\RouteEvents;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Modules\Announcement\Entities\Announcement;
use Modules\Transport\Entities\Route;

class RouteCreated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public Route $route,
        public ?int $userId = null,
        public ?string $socketId = null

    ) {}

}

