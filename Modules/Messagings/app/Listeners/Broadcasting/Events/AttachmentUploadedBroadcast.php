<?php

namespace Modules\Messagings\app\Listeners\Broadcasting\Events;

use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class AttachmentUploadedBroadcast implements ShouldBroadcast
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public int $messageId,
        public int $conversationId,
        public array $attachment,
    ) {
    }

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
        return 'attachment.uploaded';
    }

    public function broadcastWith(): array
    {
        return [
            'message_id' => $this->messageId,

            'conversation_id' => $this->conversationId,

            'attachment' => $this->attachment,
        ];
    }
}
