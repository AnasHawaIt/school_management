<?php

namespace Modules\Announcement\Listeners\AnnouncementExpiredListener;

use Modules\Announcement\Events\AnnouncementExpired;

class AnnouncementExpiredLogEventListener
{
    public function handle(AnnouncementExpired $event): void
    {
        $announcement = $event->announcement;

        activity()
            ->causedBy(auth()->user())
            ->performedOn($announcement)
            ->withProperties([
                'announcement_id' => $announcement->id,
            ])
            ->log('Announcement.');
    }
}
