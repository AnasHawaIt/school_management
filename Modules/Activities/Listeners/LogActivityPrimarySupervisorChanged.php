<?php


namespace Modules\Activities\Listeners;

use Modules\Activities\Events\ActivityPrimarySupervisorChanged;

class LogActivityPrimarySupervisorChanged
{
    public function handle(
        ActivityPrimarySupervisorChanged $event
    ): void
    {
        $supervisor = $event->supervisor;

        $activity = $supervisor->activity;

        activity()
            ->performedOn($activity)
            ->causedBy(auth()->user())
            ->withProperties([
                'activity_id' => $activity->id,
                'supervisor_id' => $supervisor->id,
                'teacher_id' => $supervisor->teacher_id,
                'is_primary' => $supervisor->is_primary,
            ])
            ->log('Activity primary supervisor changed');
    }
}
