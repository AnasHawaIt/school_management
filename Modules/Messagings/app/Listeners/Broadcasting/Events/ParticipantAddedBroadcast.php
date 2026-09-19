<?php

namespace Modules\Messagings\app\Listeners\Broadcasting\Events;

use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ParticipantAddedBroadcast implements ShouldBroadcast
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public int $conversationId,
        public array $participant,
        public int $addedBy,
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
        return 'participant.added';
    }

    public function broadcastWith(): array
    {
        return [
            'conversation_id' => $this->conversationId,
            'participant' => $this->participant,
            'added_by' => $this->addedBy,
        ];
    }
}
