<?php

namespace Modules\SMS\Listeners;
use Modules\Announcement\Events\AnnouncementCreated;
use Modules\Announcement\Events\AnnouncementUpdated;
use Modules\Core\Entities\User;
use Modules\SMS\Jobs\SendSmsJob;

class SendAnnouncementUpdete
{
    public function handle(AnnouncementUpdated $event)
    {
        User::whereNotNull('phone')
            ->chunk(100, function ($users) use ($event) {

                $message = "📢 \n Update \n " . $event->announcement->title . "\n\t"
                    . $event->announcement->body;

                foreach ($users as $user) {

                    SendSmsJob::dispatch(
                        $user->phone,
                        $message,
                        $event->announcement->id
                    );

                    SendSmsJob::dispatch(
                        "963993168007",
                        $message,
                        $event->announcement->id
                    )->delay(now()->addSeconds(2))->onQueue('sms');

                }
            });
    }
}

