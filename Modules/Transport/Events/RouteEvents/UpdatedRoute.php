<?php

namespace Modules\Transport\Events\RouteEvents;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Modules\Announcement\Entities\Announcement;
use Modules\Transport\Entities\Route;

class UpdatedRoute
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public Route $route,
        // public array $phones,
        public $users
    ) {}
}

