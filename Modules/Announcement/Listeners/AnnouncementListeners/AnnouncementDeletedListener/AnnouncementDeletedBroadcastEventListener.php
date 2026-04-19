<?php

namespace Modules\Announcement\Listeners\AnnouncementListeners\AnnouncementDeletedListener;

use Modules\Announcement\Events\AnnouncementDeleted;
use Modules\Announcement\Events\Broadcasts\AnnouncementBroadcast;

class AnnouncementDeletedBroadcastEventListener
{

    public function handle(AnnouncementDeleted $event)
    {
        broadcast(new AnnouncementBroadcast($event->announcement))->toOthers();
    }
}
