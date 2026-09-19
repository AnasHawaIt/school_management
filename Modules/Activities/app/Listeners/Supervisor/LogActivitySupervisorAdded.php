<?php

namespace Modules\Activities\app\Listeners\Supervisor;

use Modules\Activities\app\Events\ActivitySupervisorAdded;

class LogActivitySupervisorAdded
{
    public function handle(
        ActivitySupervisorAdded $event
    ): void {
        $supervisor = $event->supervisor;

        $activity = $supervisor->activity;

        activity()
            ->performedOn($activity)
            ->causedBy(auth()->user())
            ->withProperties([
                'activity_id' => $activity->id,
                'supervisor_id' => $supervisor->id,
                'teacher_id' => $supervisor->teacher_id,
                'role' => $supervisor->role,
                'is_primary' => $supervisor->is_primary,
            ])
            ->log('Activity supervisor added');
    }
}
