<?php

namespace Modules\Messagings\Listeners\MessageDeletedListener;


use Modules\Announcement\Entities\EventLogAnnouncement;
use Modules\Announcement\Events\AnnouncementDeleted;

class AnnouncementDeletedLogEventListener
{
    public function handle(AnnouncementDeleted $event)
    {
        EventLogAnnouncement::create([
            'user_id' => auth()->id(),
            'event_type' => 'announcement_deleted',
            'data' =>$event->announcement,
        ]);
    }
}
