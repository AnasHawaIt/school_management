<?php


namespace Modules\Messagings\Listeners\MessageSendedListener;


use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Announcement\Events\AnnouncementCreated;
use Modules\Announcement\Events\Broadcasts\AnnouncementBroadcast;

class AnnouncementCreatedBroadcastEventListener implements ShouldQueue
{

    public function handle(AnnouncementCreated $event)
    {
        broadcast(new AnnouncementBroadcast($event->announcement))->toOthers();
    }
}
