<?php

namespace Modules\Announcement\Listeners\AnnouncementListeners\AnnouncementDeletedListener;

use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Announcement\Events\AnnouncementDeleted;
use Modules\Announcement\Events\Broadcasts\AnnouncementBroadcast;

class AnnouncementDeletedBroadcastEventListener implements ShouldQueue
{

    public function handle(AnnouncementDeleted $event)
    {
        broadcast(new AnnouncementBroadcast($event->announcement))->toOthers();
    }
}
