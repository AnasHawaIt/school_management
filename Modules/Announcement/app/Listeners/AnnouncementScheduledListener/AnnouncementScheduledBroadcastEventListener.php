<?php

namespace Modules\Announcement\app\Listeners\AnnouncementScheduledListener;

use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Announcement\app\Events\AnnouncementScheduled;
use Modules\Announcement\app\Events\Broadcasts\AnnouncementBroadcast;

class AnnouncementScheduledBroadcastEventListener implements ShouldQueue
{
    public function handle(AnnouncementScheduled $event): void
    {
        broadcast(
            new AnnouncementBroadcast($event->announcement,'scheduled')
        )->toOthers();
    }
}
