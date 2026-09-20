<?php

namespace Modules\Announcement\app\Listeners\AnnouncementPublishedListener;

use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Announcement\app\Events\AnnouncementPublished;
use Modules\Announcement\app\Events\Broadcasts\AnnouncementBroadcast;

class AnnouncementPublishedBroadcastEventListener implements ShouldQueue
{
    public function handle(AnnouncementPublished $event): void
    {
        broadcast(
            new AnnouncementBroadcast($event->announcement,'published')
        )->toOthers();
    }
}
