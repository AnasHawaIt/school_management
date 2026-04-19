<?php


namespace Modules\Announcement\Listeners\AnnouncementListeners\AnnouncementCreatedListener;


use Modules\Announcement\Events\AnnouncementCreated;
use Modules\Announcement\Events\Broadcasts\AnnouncementBroadcast;

class AnnouncementCreatedBroadcastEventListener
{

    public function handle(AnnouncementCreated $event)
    {
        broadcast(new AnnouncementBroadcast($event->announcement))->toOthers();
    }
}
