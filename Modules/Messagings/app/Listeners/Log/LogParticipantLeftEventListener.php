<?php


namespace Modules\Messagings\app\Listeners\Log;

use Modules\Messagings\app\Events\ParticipantLeft;

class LogParticipantLeftEventListener
{
    public function handle(
        ParticipantLeft $event
    ): void
    {

        activity()
            ->causedBy($event->user)
            ->performedOn($event->conversation)
            ->log('Conversation.ParticipantLeft');
    }
}
