<?php

namespace Modules\Announcement\Listeners\AnnouncementRestoredListener;

use Modules\Announcement\Events\AnnouncementRestored;

class AnnouncementRestoredLogEventListener
{
    public function handle(AnnouncementRestored $event): void
    {
        $announcement = $event->announcement;

        activity()
            ->causedBy(auth()->user())
            ->performedOn($announcement)
            ->withProperties([
                'announcement_id' => $announcement->id,
            ])
            ->log('Announcement.restored');
    }
}
