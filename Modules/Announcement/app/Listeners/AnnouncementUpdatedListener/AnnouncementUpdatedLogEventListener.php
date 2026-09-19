<?php

namespace Modules\Announcement\app\Listeners\AnnouncementUpdatedListener;

use Modules\Announcement\app\Events\AnnouncementUpdated;

class AnnouncementUpdatedLogEventListener
{
    public function handle(AnnouncementUpdated $event): void
    {
        $announcement = $event->announcement;

        activity()
            ->causedBy(auth()->user())
            ->performedOn($announcement)
            ->withProperties([
                'announcement_id' => $announcement->id,
            ])
            ->log('Announcement.updated');
    }
}
