<?php

namespace Modules\Activities\Listeners\Participant;

use Modules\Activities\Events\ActivityParticipantAttended;

class LogActivityParticipantAttended
{
    public function handle(
        ActivityParticipantAttended $event
    ): void {
        $participant = $event->participant;

        $activity = $participant->activity;

        activity()
            ->performedOn($activity)
            ->causedBy(auth()->user())
            ->withProperties([
                'activity_id' => $activity->id,
                'participant_id' => $participant->id,
                'participant_type' => $participant->participant_type,
                'participant_user_id' => $participant->participant_id,
            ])
            ->log('Activity participant attended');
    }
}
