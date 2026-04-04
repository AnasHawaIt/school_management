<?php

namespace Modules\Transport\Events\BusEvents;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Modules\Transport\Entities\Bus;

class DeletedBus
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public Bus $bus,
        // public array $phones,
        public $users
    ) {}
}


