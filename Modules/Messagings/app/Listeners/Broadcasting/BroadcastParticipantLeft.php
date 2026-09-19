<?php


namespace Modules\Messagings\app\Listeners\Broadcasting;

use Modules\Messagings\app\Events\ParticipantLeft;
use Modules\Messagings\app\Listeners\Broadcasting\Events\ParticipantLeftBroadcast;

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
