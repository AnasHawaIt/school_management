<?php

namespace Modules\Messagings\Listeners\MessageDeletedListener;


use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Announcement\Events\AnnouncementDeleted;
use Modules\Announcement\Notifications\AnnouncementDeletedNotification;
use Modules\Core\Entities\User;

class AnnouncementDeletedNotificationDatabaseListener implements ShouldQueue
{
    public function handle(AnnouncementDeleted $event)
    {
        $map = [
            'students' => 'student',
            'teachers' => 'teacher',
            'parents'  => 'parent',
            'public'   => null,
        ];

        $role = $map[$event->announcement->audience];

        $query = User::query();

        if ($role) {
            $query->where('role', $role);
        }

        $users = $query->get();

        foreach ($users as $user) {
            $user->notify(new AnnouncementDeletedNotification($event->announcement));
        }
    }
}
