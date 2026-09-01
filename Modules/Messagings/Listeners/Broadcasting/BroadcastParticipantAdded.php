<?php

namespace Modules\Messagings\Listeners\Broadcasting;

use Modules\Messagings\Events\ParticipantAdded;
use Modules\Messagings\Listeners\Broadcasting\Events\ParticipantAddedBroadcast;

class BroadcastParticipantAdded
{
    public function handle(ParticipantAdded $event): void
    {
        ParticipantAddedBroadcast::dispatch(
            $event->conversation->id,

            [
                'id' => $event->user->id,
                'name' => $event->user->name,
                'email' => $event->user->email,
            ],

            $event->addedBy,
        );
    }
}
