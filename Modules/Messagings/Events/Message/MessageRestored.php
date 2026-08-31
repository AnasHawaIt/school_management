<?php

namespace Modules\Messagings\Events\Message;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Modules\Messagings\Entities\Message;

class MessageRestored
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public Message $message,
        public int $userId,
        public ?string $socketId = null
    ) {}

}

