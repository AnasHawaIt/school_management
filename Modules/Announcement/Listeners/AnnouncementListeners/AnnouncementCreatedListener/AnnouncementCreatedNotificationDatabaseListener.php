<?php

namespace Modules\Announcement\Listeners\AnnouncementListeners\AnnouncementCreatedListener;

use Modules\Announcement\Events\AnnouncementCreated;
use Modules\Announcement\Notifications\AnnouncementCreatedNotification;
use Modules\Core\Entities\User;

class AnnouncementCreatedNotificationDatabaseListener
{
    public function handle(AnnouncementCreated $event)
    {
        $users = User::all();

        foreach ($users as $user) {
            $user->notify(new AnnouncementCreatedNotification($event->announcement));
        }
    }
}
