<?php

namespace Modules\Library\app\Events\PublishersEvents;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Modules\Library\app\Entities\Publisher;


class PublishersUpdated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
       public Publisher $publisher,
        public ?int     $userId = null,
        public array    $changes = []
    ) {}
}

