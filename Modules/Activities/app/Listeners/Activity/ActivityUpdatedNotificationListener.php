<?php


namespace Modules\Activities\app\Listeners\Activity;

use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Activities\app\Events\ActivityUpdated;
use Modules\Notifications\app\Services\NotificationService;

class ActivityUpdatedNotificationListener implements ShouldQueue
{
    public function __construct(
        protected NotificationService $notificationService
    )
    {
    }

    public function handle(ActivityUpdated $event): void
    {
        $activity = $event->activity;

        $data = [
            'entity' => 'activity',
            'action' => 'UPDATE',
            'activity_id' => $activity->id,
        ];

        $this->notificationService->sendToAll(
            title: 'Activity Updated',
            body: $activity->title,
            type: 'activity_updated',
            data: $data
        );
    }
}
