<?php


namespace Modules\Activities\Listeners;

use Modules\Activities\Events\ActivityAttachmentUploaded;

class LogActivityAttachmentUploaded
{
    public function handle(
        ActivityAttachmentUploaded $event
    ): void
    {
        $attachment = $event->attachment;

        $activity = $attachment->activity;

        activity()
            ->performedOn($activity)
            ->causedBy(auth()->user())
            ->withProperties([
                'activity_id' => $activity->id,
                'attachment_id' => $attachment->id,
                'original_name' => $attachment->original_name,
                'file_name' => $attachment->file_name,
                'file_path' => $attachment->file_path,
                'mime_type' => $attachment->mime_type,
                'file_size' => $attachment->file_size,
                'type' => $attachment->type,
            ])
            ->log('Activity attachment uploaded');
    }
}
