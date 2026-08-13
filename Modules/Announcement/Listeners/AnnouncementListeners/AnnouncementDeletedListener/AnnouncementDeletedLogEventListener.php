<?php

namespace Modules\Announcement\Listeners\AnnouncementListeners\AnnouncementDeletedListener;

use Modules\Announcement\Events\AnnouncementDeleted;

class AnnouncementDeletedLogEventListener
{
    public function handle(AnnouncementDeleted $event)
    {
        $announcement = $event->announcement;

        activity()
            ->causedBy(auth()->user())
            ->performedOn($announcement)
            ->withProperties([
                'Announcement_id' => $announcement->id,
            ])
            ->log('Announcement.Deleted');

    }
}
