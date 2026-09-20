<?php

namespace Modules\Transport\app\Events\BusEvents;

use Modules\Transport\app\Entities\Bus;

class BusDeleted
{
    public function __construct(
        public Bus $bus,
        public ?int $userId = null
    ) {}
}


