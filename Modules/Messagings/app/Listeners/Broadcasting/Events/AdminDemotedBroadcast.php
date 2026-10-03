<?php

namespace Modules\Messagings\Listeners\Broadcasting\Events;

use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class AdminDemotedBroadcast implements ShouldBroadcast
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public int     $conversationId,
        public int     $userId,
        public ?string $userName,
        public int     $demotedBy,
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
        return 'admin.demoted';
    }

    public function broadcastWith(): array
    {
        return [
            'conversation_id' => $this->conversationId,

            'user' => [
                'id' => $this->userId,
                'name' => $this->userName,
            ],

            'role' => 'participant',

            'demoted_by' => $this->demotedBy,
        ];
    }
}
