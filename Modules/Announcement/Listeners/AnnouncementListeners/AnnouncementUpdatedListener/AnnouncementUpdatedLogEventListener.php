<?php

namespace Modules\Announcement\Listeners\AnnouncementListeners\AnnouncementUpdatedListener;

use Modules\Announcement\Events\AnnouncementUpdated;

class AnnouncementUpdatedLogEventListener
{
    public function handle(AnnouncementUpdated $event)
    {
        $announcement = $event->announcement;

        activity()
            ->causedBy(auth()->user())
            ->performedOn($announcement)
            ->withProperties([
                'Announcement_id' => $announcement->id,
            ])
            ->log('Announcement.Updated');

    }
}
