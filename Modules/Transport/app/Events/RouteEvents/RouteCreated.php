<?php

namespace Modules\Transport\app\Events\RouteEvents;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Modules\Transport\app\Entities\Route;

class RouteCreated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public Route $route,
        public ?int $userId = null,
        public ?string $socketId = null

    ) {}

}

