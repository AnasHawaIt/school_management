<?php

namespace Modules\Library\Listeners\MemberListeners;


use Modules\Core\Entities\User;
use Modules\Library\Events\MemberEvents\MemberDeleted;
use Modules\Library\Notifications\MemberDeletedNotification;

class MemberDeletedNotificationDatabaseListener
{
    public function handle(MemberDeleted $event)
    {
        $users = User::all();

        foreach ($users as $user) {
            $user->notify(new MemberDeletedNotification($event->member));
        }
    }
}
