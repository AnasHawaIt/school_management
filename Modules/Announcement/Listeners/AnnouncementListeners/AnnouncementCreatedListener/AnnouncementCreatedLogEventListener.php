<?php

namespace Modules\Announcement\Listeners\AnnouncementListeners\AnnouncementCreatedListener;

use Modules\Announcement\Entities\EventLogAnnouncement;
use Modules\Announcement\Events\AnnouncementCreated;

class AnnouncementCreatedLogEventListener
{
    public function handle(AnnouncementCreated $event)
    {
        EventLogAnnouncement::create([
            'user_id' => $event->userId,
            'event_type' => 'subscription_created',
            'data' =>$event->announcement,
        ]);
    }
}
