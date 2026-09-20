<?php


namespace Modules\Messagings\app\Listeners\Broadcasting;

use Modules\Messagings\app\Events\ParticipantRemoved;
use Modules\Messagings\app\Listeners\Broadcasting\Events\ParticipantRemovedBroadcast;

class BroadcastParticipantRemoved
{
    public function handle(ParticipantRemoved $event): void
    {
        ParticipantRemovedBroadcast::dispatch(
            conversationId: $event->conversation->id,
            userId: $event->user->id,
            userName: $event->user->name,
            removedBy: $event->removedBy,
        );
    }
}
