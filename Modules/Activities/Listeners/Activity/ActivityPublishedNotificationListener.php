<?php


namespace Modules\Activities\Listeners\Activity;

use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Activities\Events\ActivityPublished;
use Modules\Notifications\Services\NotificationService;

class ActivityPublishedNotificationListener implements ShouldQueue
{
    public function __construct(
        protected NotificationService $notificationService
    )
    {
    }

    public function handle(ActivityPublished $event): void
    {
        $activity = $event->activity;

        $data = [
            'entity' => 'activity',
            'action' => 'PUBLISH',
            'activity_id' => $activity->id,
        ];

        $this->notificationService->sendToAll(
            title: 'Activity Published',
            body: $activity->title,
            type: 'activity_published',
            data: $data
        );
    }
}
