<?php

namespace Modules\SMS\Listeners;
use Modules\Announcement\Events\AuthorDeleted;
use Modules\Core\Entities\User;
use Modules\SMS\Jobs\SendSmsJob;

class SendAnnouncementDelete
{
    public function handle(AuthorDeleted $event)
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
                        "963993168007",
                        $message,
                        $event->announcement->id
                    )->delay(now()->addSeconds(2))->onQueue('sms');


                }
            });
    }
}

