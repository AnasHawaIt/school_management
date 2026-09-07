<?php

namespace Modules\Announcement\Listeners\AnnouncementRestoredListener;

use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Announcement\Events\AnnouncementRestored;
use Modules\Announcement\Events\Broadcasts\AnnouncementBroadcast;

class AnnouncementRestoredBroadcastEventListener implements ShouldQueue
{
    public function handle(AnnouncementRestored $event): void
    {
        broadcast(
            new AnnouncementBroadcast($event->announcement,'restored')
        )->toOthers();
    }
}
