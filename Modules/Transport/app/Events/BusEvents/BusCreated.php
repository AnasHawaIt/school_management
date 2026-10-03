<?php

namespace Modules\Transport\Events\BusEvents;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Modules\Transport\Entities\Bus;

class BusCreated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public Bus $bus,
        public ?int $userId = null,
        public ?string $socketId = null
    ) {}
}
