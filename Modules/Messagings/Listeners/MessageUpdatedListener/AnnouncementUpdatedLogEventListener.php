<?php

namespace Modules\Messagings\Listeners\MessageUpdatedListener;

use Modules\Announcement\Entities\EventLogAnnouncement;
use Modules\Announcement\Events\AnnouncementUpdated;

class AnnouncementUpdatedLogEventListener
{
    public function handle(AnnouncementUpdated $event)
    {
        EventLogAnnouncement::create([
            'user_id' => auth()->id(),
            'event_type' =>'announcement_updated',
            'data' =>$event->announcement,
        ]);
    }
}
