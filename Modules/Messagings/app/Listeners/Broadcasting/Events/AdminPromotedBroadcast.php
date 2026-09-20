<?php

namespace Modules\Messagings\app\Listeners\Broadcasting\Events;

use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class AdminPromotedBroadcast implements ShouldBroadcast
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public int     $conversationId,
        public int     $userId,
        public ?string $userName,
        public int     $promotedBy,
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
        return 'admin.promoted';
    }

    public function broadcastWith(): array
    {
        return [
            'conversation_id' => $this->conversationId,

            'user' => [
                'id' => $this->userId,
                'name' => $this->userName,
            ],

            'role' => 'admin',

            'promoted_by' => $this->promotedBy,
        ];
    }
}
