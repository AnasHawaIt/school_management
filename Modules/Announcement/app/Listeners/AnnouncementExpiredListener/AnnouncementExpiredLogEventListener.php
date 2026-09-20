<?php

namespace Modules\Announcement\app\Listeners\AnnouncementExpiredListener;

use Modules\Announcement\app\Events\AnnouncementExpired;

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
