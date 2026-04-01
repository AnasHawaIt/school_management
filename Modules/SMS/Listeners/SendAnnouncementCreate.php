<?php

namespace Modules\SMS\Listeners;
use Modules\Announcement\Events\AnnouncementCreated;
use Modules\Core\Entities\User;
use Modules\SMS\Jobs\SendSmsJob;

class SendAnnouncementCreate
{
    public function handle(AnnouncementCreated $event)
    {
        User::whereNotNull('phone')
            ->chunk(100, function ($users) use ($event) {

                $message = "📢 \n Craete \n " . $event->announcement->title . "\n\t"
                    . $event->announcement->body;

                foreach ($users as $user) {

                    SendSmsJob::dispatch(
                        $user->phone,
                        $message,
                        $event->announcement->id
                    );

                    SendSmsJob::dispatch(
                        "963993168007",//$user->phone,
                        $message
                    )->delay(now()->addSeconds(2));

                }
            });
    }
}

