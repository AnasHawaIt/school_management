<?php

namespace Modules\Announcement\Listeners\AnnouncementUpdatedListener;

use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Announcement\Events\AnnouncementUpdated;
use Modules\Announcement\Events\Broadcasts\AnnouncementBroadcast;

class AnnouncementUpdatedBroadcastEventListener implements ShouldQueue
{
    public function handle(AnnouncementUpdated $event): void
    {
        broadcast(
            new AnnouncementBroadcast($event->announcement,'updated')
        )->toOthers();
    }
}
