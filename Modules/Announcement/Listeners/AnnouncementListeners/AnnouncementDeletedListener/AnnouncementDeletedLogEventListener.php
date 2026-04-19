<?php

namespace Modules\Announcement\Listeners\AnnouncementListeners\AnnouncementDeletedListener;


use Modules\Announcement\Entities\EventLogAnnouncement;
use Modules\Announcement\Events\AnnouncementDeleted;

class AnnouncementDeletedLogEventListener
{
    public function handle(AnnouncementDeleted $event)
    {
        EventLogAnnouncement::create([
            'user_id' => $event->userId,
            'event_type' => 'Announcement Deleted',
            'data' =>$event->announcement,
        ]);
    }
}
