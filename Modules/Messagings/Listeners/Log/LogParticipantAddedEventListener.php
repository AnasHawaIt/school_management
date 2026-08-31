<?php


namespace Modules\Messagings\Listeners\Log;

use Modules\Core\Entities\User;
use Modules\Messagings\Events\ParticipantAdded;

class LogParticipantAddedEventListener
{
    public function handle(
        ParticipantAdded $event
    ): void
    {

        $user = User::find($event->addedBy);

        activity()
            ->causedBy($user)
            ->performedOn($event->conversation)
            ->withProperties([
                'participant_id' => $event->user->id,
            ])
            ->log('Conversation.ParticipantAdded');
    }
}
