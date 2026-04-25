<?php


namespace Modules\Announcement\Listeners\AnnouncementListeners\AnnouncementUpdatedListener;

use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Announcement\Events\AnnouncementUpdated;
use Modules\Announcement\Events\Broadcasts\AnnouncementBroadcast;

class AnnouncementUpdatedBroadcastEventListener implements ShouldQueue
{

    public function handle(AnnouncementUpdated $event)
    {
        broadcast(new AnnouncementBroadcast($event->announcement))->toOthers();
    }
}
