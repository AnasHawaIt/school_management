<?php

namespace Modules\Announcement\Listeners\AnnouncementCreatedListener;

use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Announcement\Events\AnnouncementCreated;
use Modules\Announcement\Events\Broadcasts\AnnouncementBroadcast;

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
