<?php

namespace Modules\Transport\app\Events\RouteEvents;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Modules\Transport\app\Entities\Route;

class RouteUpdated
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public Route $route,
        public ?int $userId = null,
        public array $changes = []
    ) {}
}

