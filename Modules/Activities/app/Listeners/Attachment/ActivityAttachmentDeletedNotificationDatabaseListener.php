<?php


namespace Modules\Activities\app\Listeners\Attachment;

use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Activities\app\Events\ActivityAttachmentDeleted;
use Modules\Notifications\app\Services\NotificationService;

class ActivityAttachmentDeletedNotificationDatabaseListener implements ShouldQueue
{
    public function __construct(
        protected NotificationService $notificationService
    )
    {
    }

    public function handle(ActivityAttachmentDeleted $event): void
    {
        $attachment = $event->attachment;

        $activity = $attachment->activity;

        $data = [
            'entity' => 'activity',
            'action' => 'ATTACHMENT_DELETED',
            'activity_id' => $activity->id,
            'attachment_id' => $attachment->id,
        ];

        $this->notificationService->sendToAll(
            title: 'Activity Attachment Deleted',
            body: $attachment->original_name,
            type: 'activity_attachment_deleted',
            data: $data
        );
    }
}
