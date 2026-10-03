<?php

namespace Modules\Announcement\Listeners\AnnouncementPublishedListener;

use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Announcement\Events\AnnouncementPublished;
use Modules\Announcement\Events\Broadcasts\AnnouncementBroadcast;

class AnnouncementPublishedBroadcastEventListener implements ShouldQueue
{
    public function handle(AnnouncementPublished $event): void
    {
        broadcast(
            new AnnouncementBroadcast($event->announcement,'published')
        )->toOthers();
    }
}
