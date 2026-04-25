<?php

namespace Modules\Announcement\Listeners\AnnouncementListeners\AnnouncementUpdatedListener;


use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Announcement\Events\AnnouncementUpdated;
use Modules\Announcement\Notifications\AnnouncementUpdateNotification;
use Modules\Core\Entities\User;

class AnnouncementUpdatedNotificationDatabaseListener implements ShouldQueue
{
    public function handle(AnnouncementUpdated $event)
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
            $user->notify(new AnnouncementUpdateNotification($event->announcement));
        }
    }
}
