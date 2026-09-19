<?php


namespace Modules\Activities\app\Listeners\Activity;

use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Activities\app\Events\ActivityCancelled;
use Modules\Notifications\app\Services\NotificationService;

class ActivityCancelledNotificationListener implements ShouldQueue
{
    public function __construct(
        protected NotificationService $notificationService
    )
    {
    }

    public function handle(ActivityCancelled $event): void
    {
        $activity = $event->activity;

        $data = [
            'entity' => 'activity',
            'action' => 'CANCEL',
            'activity_id' => $activity->id,
        ];

        $this->notificationService->sendToAll(
            title: 'Activity Cancelled',
            body: $activity->title,
            type: 'activity_cancelled',
            data: $data
        );
    }
}
