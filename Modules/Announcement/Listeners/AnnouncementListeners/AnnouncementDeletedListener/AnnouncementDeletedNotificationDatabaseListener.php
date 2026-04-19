<?php

namespace Modules\Announcement\Listeners\AnnouncementListeners\AnnouncementDeletedListener;


use Modules\Announcement\Events\AnnouncementDeleted;
use Modules\Announcement\Notifications\AnnouncementDeletedNotification;
use Modules\Core\Entities\User;

class AnnouncementDeletedNotificationDatabaseListener
{
    public function handle(AnnouncementDeleted $event)
    {
        $users = User::all();

        foreach ($users as $user) {
            $user->notify(new AnnouncementDeletedNotification($event->announcement));
        }
    }
}
