<?php


namespace Modules\Activities\app\Listeners\Attachment;

use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Activities\app\Events\ActivityAttachmentUploaded;
use Modules\Notifications\app\Services\NotificationService;

class ActivityAttachmentUploadedNotificationDatabaseListener implements ShouldQueue
{
    public function __construct(
        protected NotificationService $notificationService
    )
    {
    }

    public function handle(ActivityAttachmentUploaded $event): void
    {
        $attachment = $event->attachment;

        $activity = $attachment->activity;

        $data = [
            'entity' => 'activity',
            'action' => 'ATTACHMENT_UPLOADED',
            'activity_id' => $activity->id,
            'attachment_id' => $attachment->id,
            'type' => $attachment->type,
        ];

        $this->notificationService->sendToAll(
            title: 'New Activity Attachment',
            body: $attachment->original_name,
            type: 'activity_attachment_uploaded',
            data: $data
        );
    }
}
