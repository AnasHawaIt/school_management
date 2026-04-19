<?php

namespace Modules\Announcement\Listeners\AnnouncementListeners\AnnouncementUpdatedListener;

use Modules\Announcement\Entities\EventLogAnnouncement;
use Modules\Announcement\Events\AnnouncementUpdated;

class AnnouncementUpdatedLogEventListener
{
    public function handle(AnnouncementUpdated $event)
    {
        EventLogAnnouncement::create([
            'user_id' => $event->userId,
            'event_type' => 'TransactionUpdated',
            'data' =>$event->announcement,
        ]);
    }
}
