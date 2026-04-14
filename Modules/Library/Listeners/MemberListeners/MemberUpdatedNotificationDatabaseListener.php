<?php

namespace Modules\Library\Listeners\MemberListeners;


use Modules\Core\Entities\User;
use Modules\Library\Events\MemberEvents\MemberUpdated;
use Modules\Library\Notifications\MemberUpdatedNotification;

class MemberUpdatedNotificationDatabaseListener
{
    public function handle(MemberUpdated $event)
    {
        $users = User::all();

        foreach ($users as $user) {
            $user->notify(new MemberUpdatedNotification($event->member));
        }
    }
}
