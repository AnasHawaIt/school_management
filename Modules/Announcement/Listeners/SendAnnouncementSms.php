<?php

namespace Modules\Announcement\Listeners;

use Modules\Announcement\Events\AnnouncementCreated;
use Modules\Core\Entities\User;
use Modules\SMS\Jobs\SendSmsJob;

class SendAnnouncementSms
{
    public function handle(AnnouncementCreated $event)
    {
        $announcement = $event->announcement;

        User::select('id', 'phone')
            ->whereNotNull('phone')
            ->chunk(100, function ($users) use ($announcement) {

                foreach ($users as $user) {

                    SendSmsJob::dispatch(
                        $user->phone,
                        $announcement->body,
                        $announcement->id
                    )->onQueue('sms');
                }

            });
    }
}
