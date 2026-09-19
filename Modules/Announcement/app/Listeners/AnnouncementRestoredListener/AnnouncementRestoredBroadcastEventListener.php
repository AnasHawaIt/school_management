<?php

namespace Modules\Announcement\app\Listeners\AnnouncementRestoredListener;

use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Announcement\app\Events\AnnouncementRestored;
use Modules\Announcement\app\Events\Broadcasts\AnnouncementBroadcast;

class AnnouncementRestoredBroadcastEventListener implements ShouldQueue
{
    public function handle(AnnouncementRestored $event): void
    {
        broadcast(
            new AnnouncementBroadcast($event->announcement,'restored')
        )->toOthers();
    }
}
