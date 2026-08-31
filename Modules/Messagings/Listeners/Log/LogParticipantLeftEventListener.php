<?php


namespace Modules\Messagings\Listeners\Log;

use Modules\Messagings\Events\ParticipantLeft;

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
