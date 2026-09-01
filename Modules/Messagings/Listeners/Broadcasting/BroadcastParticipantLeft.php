<?php


namespace Modules\Messagings\Listeners\Broadcasting;

use Modules\Messagings\Events\ParticipantLeft;
use Modules\Messagings\Listeners\Broadcasting\Events\ParticipantLeftBroadcast;

class BroadcastParticipantLeft
{
    public function handle(ParticipantLeft $event): void
    {
        ParticipantLeftBroadcast::dispatch(
            conversationId: $event->conversation->id,
            userId: $event->user->id,
            userName: $event->user->name,
        );
    }
}
