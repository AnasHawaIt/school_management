<?php


namespace Modules\Activities\app\Listeners\Activity;

use Modules\Activities\app\Events\ActivityPublished;

class LogActivityPublished
{
    public function handle(
        ActivityPublished $event
    ): void
    {
        $activity = $event->activity;

        activity()
            ->performedOn($activity)
            ->causedBy(auth()->user())
            ->withProperties([
                'activity_id' => $activity->id,
                'title' => $activity->title,
            ])
            ->log('Activity.Published');
    }
}
