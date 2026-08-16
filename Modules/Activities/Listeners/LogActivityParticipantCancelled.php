<?php

namespace Modules\Activities\Listeners;

use Modules\Activities\Events\ActivityParticipantCancelled;

class LogActivityParticipantCancelled
{
    public function handle(
        ActivityParticipantCancelled $event
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
            ->log('Activity participant cancelled.');
    }
}
