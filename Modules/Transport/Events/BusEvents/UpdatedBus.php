<?php

namespace Modules\Transport\Events\BusEvents;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Modules\Transport\Entities\Bus;

class UpdatedBus
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public Bus $bus,
        // public array $phones,
        public $users
    ) {}
}

