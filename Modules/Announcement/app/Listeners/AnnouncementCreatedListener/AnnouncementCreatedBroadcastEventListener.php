<?php

namespace Modules\Announcement\app\Listeners\AnnouncementCreatedListener;

use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Announcement\app\Events\AnnouncementCreated;
use Modules\Announcement\app\Events\Broadcasts\AnnouncementBroadcast;

class AnnouncementCreatedBroadcastEventListener implements ShouldQueue
{
    public function handle(AnnouncementCreated $event): void
    {
        broadcast(
            new AnnouncementBroadcast(
                $event->announcement,
                'created'
            )
        )->toOthers();
    }
}
