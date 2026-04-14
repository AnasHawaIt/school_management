<?php

namespace Modules\Transport\Events\BusEvents;

use Modules\Transport\Entities\Bus;

class BusDeleted
{
    public function __construct(
        public Bus $bus,
        public ?int $userId = null
    ) {}
}


