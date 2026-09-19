<?php

namespace Modules\Messagings\app\Listeners\Broadcasting\Events;

use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MessageRestoredBroadcast implements ShouldBroadcast
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public int $messageId,
        public int $conversationId,
        public int $restoredBy,
    ) {}

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel(
                "conversation.{$this->conversationId}"
            ),
        ];
    }

    public function broadcastAs(): string
    {
        return 'message.restored';
    }
}
