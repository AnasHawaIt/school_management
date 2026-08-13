<?php

namespace Modules\Announcement\Listeners\AnnouncementListeners\AnnouncementCreatedListener;

use Modules\Announcement\Events\AnnouncementCreated;

class AnnouncementCreatedLogEventListener
{
    public function handle(AnnouncementCreated $event)
    {
        $announcement = $event->announcement;

        activity()
            ->causedBy(auth()->user())
            ->performedOn($announcement)
            ->withProperties([
                'Announcement_id' => $announcement->id,
            ])
            ->log('Announcement.created');
    }
}
