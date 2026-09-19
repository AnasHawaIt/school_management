<?php


namespace Modules\Messagings\app\Listeners\Log;

use Modules\Core\app\Entities\User;
use Modules\Messagings\app\Events\ParticipantAdded;

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
