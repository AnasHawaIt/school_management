<?php

namespace Modules\Announcement\app\Listeners\AnnouncementCreatedListener;

use Modules\Announcement\app\Events\AnnouncementCreated;

class AnnouncementCreatedLogEventListener
{
    public function handle(AnnouncementCreated $event): void
    {
        $announcement = $event->announcement;

        activity()
            ->causedBy(auth()->user())
            ->performedOn($announcement)
            ->withProperties([
                'announcement_id' => $announcement->id,
            ])
            ->log('Announcement.created');
    }
}
