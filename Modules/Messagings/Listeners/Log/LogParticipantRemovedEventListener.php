<?php


namespace Modules\Messagings\Listeners\Log;

use Modules\Core\Entities\User;
use Modules\Messagings\Events\ParticipantRemoved;

class LogParticipantRemovedEventListener
{
    public function handle(
        ParticipantRemoved $event
    ): void
    {

        $user = User::find($event->removedBy);

        activity()
            ->causedBy($user)
            ->performedOn($event->conversation)
            ->withProperties([
                'participant_id' => $event->user->id,
            ])
            ->log('Conversation.ParticipantRemoved');
    }
}
