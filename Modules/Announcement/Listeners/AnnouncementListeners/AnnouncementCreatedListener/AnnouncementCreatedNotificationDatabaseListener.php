<?php

namespace Modules\Announcement\Listeners\AnnouncementListeners\AnnouncementCreatedListener;

use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Announcement\Events\AnnouncementCreated;
use Modules\Announcement\Notifications\AnnouncementCreatedNotification;
use Modules\Core\Entities\User;

class AnnouncementCreatedNotificationDatabaseListener implements ShouldQueue
{
    public function handle(AnnouncementCreated $event)
    {
        $map = [
            'students' => 'student',
            'teachers' => 'teacher',
            'parents'  => 'parent',
            'public'   => null,
        ];

        $audience = $event->announcement->audience;

        if (!array_key_exists($audience, $map)) {
            throw new \InvalidArgumentException(
                "Invalid announcement audience: {$audience}"
            );
        }

        $role = $map[$audience];

        $query = User::query();

        if ($role) {
            $query->where('role', $role);
        }

        $users = $query->get();

        foreach ($users as $user) {
            $user->notify(new AnnouncementCreatedNotification($event->announcement));
        }
    }
}
