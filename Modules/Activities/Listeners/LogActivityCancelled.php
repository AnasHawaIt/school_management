<?php


namespace Modules\Activities\Listeners;


use Modules\Activities\Events\ActivityCancelled;

class LogActivityCancelled
{
    public function handle(
        ActivityCancelled $event
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
            ->log('Activity.Canceled');
    }
}
