<?php

namespace Modules\Transport\Events\RouteEvents;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Modules\Announcement\Entities\Announcement;
use Modules\Transport\Entities\Route;

class DeletedRoute
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public Route $route,
        // public array $phones,
        public $users
    ) {}
}


