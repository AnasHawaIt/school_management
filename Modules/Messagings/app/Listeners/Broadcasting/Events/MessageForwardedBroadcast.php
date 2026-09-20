<?php

namespace Modules\Messagings\app\Listeners\Broadcasting\Events;

use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Modules\Messagings\app\Entities\Message;

class MessageForwardedBroadcast implements ShouldBroadcast
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public Message $message,
        public int $senderId,
    ) {
    }

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel(
                "conversation.{$this->message->conversation_id}"
            ),
        ];
    }

    public function broadcastAs(): string
    {
        return 'message.forwarded';
    }

    public function broadcastWith(): array
    {
        return [
            'message' => $this->message->toArray(),
            'sender_id' => $this->senderId,
        ];
    }
}
