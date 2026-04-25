<?php

namespace Modules\Library\Listeners\MemberListeners;


use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Core\Entities\User;
use Modules\Library\Events\MemberEvents\MemberDeleted;
use Modules\Library\Notifications\MemberDeletedNotification;

class MemberDeletedNotificationDatabaseListener  implements ShouldQueue
{
    public function handle(MemberDeleted $event)
    {
        $users = User::all();

        foreach ($users as $user) {
            $user->notify(new MemberDeletedNotification($event->member));
        }
    }
}
