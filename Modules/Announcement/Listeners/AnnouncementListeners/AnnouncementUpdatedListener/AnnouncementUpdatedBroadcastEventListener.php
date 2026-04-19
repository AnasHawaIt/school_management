<?php


namespace Modules\Announcement\Listeners\AnnouncementListeners\AnnouncementUpdatedListener;

use Modules\Announcement\Events\AnnouncementUpdated;
use Modules\Announcement\Events\Broadcasts\AnnouncementBroadcast;

class AnnouncementUpdatedBroadcastEventListener
{

    public function handle(AnnouncementUpdated $event)
    {
        broadcast(new AnnouncementBroadcast($event->announcement))->toOthers();
    }
}
