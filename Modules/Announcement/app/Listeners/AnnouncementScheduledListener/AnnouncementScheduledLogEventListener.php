<?php

namespace Modules\Announcement\app\Listeners\AnnouncementScheduledListener;

use Modules\Announcement\app\Events\AnnouncementScheduled;

class AnnouncementScheduledLogEventListener
{
    public function handle(AnnouncementScheduled $event): void
    {
        $announcement = $event->announcement;

        activity()
            ->causedBy(auth()->user())
            ->performedOn($announcement)
            ->withProperties([
                'announcement_id' => $announcement->id,
            ])
            ->log('Announcement.Scheduled');
    }
}
