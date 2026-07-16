<?php

namespace Modules\Messagings\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Modules\Messagings\Entities\Message;

class MessagesDeleted
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public Message $Message,
        public ?string $socketId = null
    ) {}
}


