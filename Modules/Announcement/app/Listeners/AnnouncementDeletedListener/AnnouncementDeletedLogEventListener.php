<?php

namespace Modules\Announcement\app\Listeners\AnnouncementDeletedListener;

use Modules\Announcement\app\Events\AnnouncementDeleted;

class AnnouncementDeletedLogEventListener
{
    public function handle(AnnouncementDeleted $event): void
    {
        $announcement = $event->announcement;

        activity()
            ->causedBy(auth()->user())
            ->performedOn($announcement)
            ->withProperties([
                'announcement_id' => $announcement->id,
            ])
            ->log('Announcement.deleted');
    }
}
