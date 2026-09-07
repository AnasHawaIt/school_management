<?php

namespace Modules\Announcement\Listeners\AnnouncementExpiredListener;

use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Announcement\Events\AnnouncementExpired;
use Modules\Announcement\Events\Broadcasts\AnnouncementBroadcast;

class AnnouncementExpiredBroadcastEventListener implements ShouldQueue
{
    public function handle(AnnouncementExpired $event): void
    {
        broadcast(
            new AnnouncementBroadcast($event->announcement,'expired')
        )->toOthers();
    }
}
