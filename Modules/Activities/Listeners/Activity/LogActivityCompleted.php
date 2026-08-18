<?php


namespace Modules\Activities\Listeners\Activity;


use Modules\Activities\Events\ActivityCompleted;

class LogActivityCompleted
{
    public function handle(
        ActivityCompleted $event
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
            ->log('Activity.Completed');
    }
}
