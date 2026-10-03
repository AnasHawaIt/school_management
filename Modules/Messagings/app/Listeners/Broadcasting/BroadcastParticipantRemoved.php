<?php


namespace Modules\Messagings\Listeners\Broadcasting;

use Modules\Messagings\Events\ParticipantRemoved;
use Modules\Messagings\Listeners\Broadcasting\Events\ParticipantRemovedBroadcast;

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
