<?php

namespace Modules\Announcement\Listeners\AnnouncementScheduledListener;

use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Announcement\Events\AnnouncementScheduled;
use Modules\Announcement\Events\Broadcasts\AnnouncementBroadcast;

class AnnouncementScheduledBroadcastEventListener implements ShouldQueue
{
    public function handle(AnnouncementScheduled $event): void
    {
        broadcast(
            new AnnouncementBroadcast($event->announcement,'scheduled')
        )->toOthers();
    }
}
