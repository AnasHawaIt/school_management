<?php


namespace Modules\Activities\Listeners\Activity;


use Modules\Activities\Events\ActivityUpdated;

class LogActivityUpdated
{
    public function handle(
        ActivityUpdated $event
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
            ->log('Activity.Updated');
    }
}
