<?php

namespace Modules\Announcement\Listeners\AnnouncementPublishedListener;

use Modules\Announcement\Events\AnnouncementPublished;

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
