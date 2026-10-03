<?php

namespace Modules\Activities\Listeners\Activity;

use Modules\Activities\Events\ActivityStarted;

class LogActivityStarted
{
    public function handle(
        ActivityStarted $event
    ): void {
        $activity = $event->activity;

        activity()
            ->performedOn($activity)
            ->causedBy(auth()->user())
            ->withProperties([
                'activity_id' => $activity->id,
                'title' => $activity->title,
            ])
            ->log('Activity.Started');
    }
}
