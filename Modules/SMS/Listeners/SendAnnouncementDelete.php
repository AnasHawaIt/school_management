<?php

namespace Modules\SMS\Listeners;
use Modules\Announcement\Events\AnnouncementDeleted;
use Modules\Core\Entities\User;
use Modules\SMS\Jobs\SendSmsJob;

class SendAnnouncementDelete
{
    public function handle(AnnouncementDeleted $event)
    {
        User::whereNotNull('phone')
            ->chunk(100, function ($users) use ($event) {

                $message = "📢"." \n "." Delete "." \n \t "." successfully ";

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

