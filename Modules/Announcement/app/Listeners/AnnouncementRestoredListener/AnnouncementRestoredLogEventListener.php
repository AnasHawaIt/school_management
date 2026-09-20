<?php

namespace Modules\Announcement\app\Listeners\AnnouncementRestoredListener;

use Modules\Announcement\app\Events\AnnouncementRestored;

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
