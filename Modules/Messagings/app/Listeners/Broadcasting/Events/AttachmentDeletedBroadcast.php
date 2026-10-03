<?php

namespace Modules\Messagings\Listeners\Broadcasting\Events;

use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class AttachmentDeletedBroadcast implements ShouldBroadcast
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public int $attachmentId,
        public int $messageId,
        public int $conversationId,
        public string $fileName,
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
        return 'attachment.deleted';
    }

    public function broadcastWith(): array
    {
        return [
            'attachment_id' => $this->attachmentId,
            'message_id' => $this->messageId,
            'conversation_id' => $this->conversationId,
            'file_name' => $this->fileName,
        ];
    }
}
