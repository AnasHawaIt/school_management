<?php


namespace Modules\Activities\Listeners\Attachment;

use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Activities\Events\ActivityAttachmentDeleted;
use Modules\Notifications\Services\NotificationService;

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
