<?php

namespace Modules\Announcement\app\Listeners\AnnouncementExpiredListener;

use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Announcement\app\Events\AnnouncementExpired;
use Modules\Announcement\app\Events\Broadcasts\AnnouncementBroadcast;

class AnnouncementExpiredBroadcastEventListener implements ShouldQueue
{
    public function handle(AnnouncementExpired $event): void
    {
        broadcast(
            new AnnouncementBroadcast($event->announcement,'expired')
        )->toOthers();
    }
}
