<?php

namespace Modules\Announcement\app\Listeners\AnnouncementDeletedListener;

use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Announcement\app\Events\AnnouncementDeleted;
use Modules\Announcement\app\Events\Broadcasts\AnnouncementBroadcast;

class AnnouncementDeletedBroadcastEventListener implements ShouldQueue
{
    public function handle(AnnouncementDeleted $event): void
    {
        broadcast(
            new AnnouncementBroadcast($event->announcement,'deleted')
        )->toOthers();
    }
}
