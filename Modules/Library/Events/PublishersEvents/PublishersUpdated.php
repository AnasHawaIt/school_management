<?php

namespace Modules\Library\Events\PublishersEvents;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Modules\Library\Entities\Publishers;


class PublishersUpdated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
       public Publishers $publisher,
        public ?int $userId = null,
        public array $changes = []
    ) {}
}

