<?php

namespace Modules\Messagings\app\Events\Message;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Modules\Messagings\app\Entities\Message;

class MessageFailed
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public Message $message,
        public ?int $userId = null,
    ) {}
}
