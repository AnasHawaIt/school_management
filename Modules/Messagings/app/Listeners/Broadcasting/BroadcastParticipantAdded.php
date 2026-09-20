<?php

namespace Modules\Messagings\app\Listeners\Broadcasting;

use Modules\Messagings\app\Events\ParticipantAdded;
use Modules\Messagings\app\Listeners\Broadcasting\Events\ParticipantAddedBroadcast;

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
