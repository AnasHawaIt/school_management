<?php

namespace Modules\Messagings\Listeners\Broadcasting\Events;

use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ConversationCreatedBroadcast implements ShouldBroadcast
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public int     $conversationId,
        public string  $type,
        public ?string $title,
        public int     $createdBy,
        public ?string $createdAt,
    )
    {
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
        return 'conversation.created';
    }

    public function broadcastWith(): array
    {
        return [
            'conversation' => [
                'id' => $this->conversationId,
                'type' => $this->type,
                'title' => $this->title,
                'created_by' => $this->createdBy,
                'created_at' => $this->createdAt,
            ],
        ];
    }
}
