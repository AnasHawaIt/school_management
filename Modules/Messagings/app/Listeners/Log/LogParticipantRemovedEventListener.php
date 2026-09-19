<?php


namespace Modules\Messagings\app\Listeners\Log;

use Modules\Core\app\Entities\User;
use Modules\Messagings\app\Events\ParticipantRemoved;

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
