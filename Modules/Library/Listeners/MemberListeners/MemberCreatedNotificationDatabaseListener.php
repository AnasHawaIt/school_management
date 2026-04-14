<?php

namespace Modules\Library\Listeners\MemberListeners;


use Modules\Core\Entities\User;
use Modules\Library\Events\MemberEvents\MemberCreated;
use Modules\Library\Notifications\MemberCreatedNotification;

class MemberCreatedNotificationDatabaseListener
{
    public function handle(MemberCreated $event)
    {
        $users = User::all();

        foreach ($users as $user) {
            $user->notify(new MemberCreatedNotification($event->member));
        }
    }
}
