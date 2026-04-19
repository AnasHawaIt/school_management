<?php

namespace Modules\Announcement\Listeners\AnnouncementListeners\AnnouncementUpdatedListener;


use Modules\Announcement\Events\AnnouncementUpdated;
use Modules\Announcement\Notifications\AnnouncementUpdateNotification;
use Modules\Core\Entities\User;

class AnnouncementUpdatedNotificationDatabaseListener
{
    public function handle(AnnouncementUpdated $event)
    {
        $users = User::all();

        foreach ($users as $user) {
            $user->notify(new AnnouncementUpdateNotification($event->announcement));
        }
    }
}
