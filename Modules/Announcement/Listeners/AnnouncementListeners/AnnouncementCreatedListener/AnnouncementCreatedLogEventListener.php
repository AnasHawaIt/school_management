<?php

namespace Modules\Announcement\Listeners\AnnouncementListeners\AnnouncementCreatedListener;

use Modules\Announcement\Entities\EventLogAnnouncement;
use Modules\Announcement\Events\AnnouncementCreated;

class AnnouncementCreatedLogEventListener
{
    public function handle(AnnouncementCreated $event)
    {
        EventLogAnnouncement::create([
            'user_id' => auth()->id(),
            'event_type' => 'announcement_created',
            'data' =>$event->announcement,
        ]);
    }
}
