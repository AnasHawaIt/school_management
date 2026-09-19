<?php

namespace Modules\Announcement\app\Listeners\AnnouncementPublishedListener;

use Modules\Announcement\app\Events\AnnouncementPublished;

class AnnouncementPublishedLogEventListener
{
    public function handle(AnnouncementPublished $event): void
    {
        $announcement = $event->announcement;

        activity()
            ->causedBy(auth()->user())
            ->performedOn($announcement)
            ->withProperties([
                'announcement_id' => $announcement->id,
            ])
            ->log('Announcement.published');
    }
}
