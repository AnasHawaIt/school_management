<?php

namespace Modules\Announcement\app\Listeners\AnnouncementUpdatedListener;

use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Announcement\app\Events\AnnouncementUpdated;
use Modules\Announcement\app\Events\Broadcasts\AnnouncementBroadcast;

class AnnouncementUpdatedBroadcastEventListener implements ShouldQueue
{
    public function handle(AnnouncementUpdated $event): void
    {
        broadcast(
            new AnnouncementBroadcast($event->announcement,'updated')
        )->toOthers();
    }
}
