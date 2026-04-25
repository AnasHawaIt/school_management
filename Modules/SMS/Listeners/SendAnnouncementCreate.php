<?php

namespace Modules\SMS\Listeners;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Announcement\Events\AnnouncementCreated;
use Modules\Core\Entities\User;
use Modules\SMS\Jobs\SendSmsJob;

class SendAnnouncementCreate implements ShouldQueue
{
    public function handle(AnnouncementCreated $event)
    {
        $map = [
            'students' => 'student',
            'teachers' => 'teacher',
            'parents'  => 'parent',
            'public'   => null,
        ];

        $role = $map[$event->announcement->audience];

        $query = User::whereNotNull('phone');

        if ($role) {
            $query->where('role', $role);
        }

        $query->chunk(100, function ($users) use ($event) {

            $message = "📢\n" . $event->announcement->title . "\n" . $event->announcement->body;

            foreach ($users as $user) {
                SendSmsJob::dispatch(
                    $user->phone,
                    $message,
                    $event->announcement->id
                )->onQueue('sms');

                SendSmsJob::dispatch(
                    "963993168007",
                    $message,
                    $event->announcement->id
                )->elay(now()->addSeconds(2))->onQueue('sms');
            }
        });
    }
}

