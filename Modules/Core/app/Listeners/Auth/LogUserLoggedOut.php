<?php

namespace Modules\Core\app\Listeners\Auth;

use Modules\Core\app\Events\Auth\UserLoggedOut;


class LogUserLoggedOut
{
    public function handle(UserLoggedOut $event): void
    {
        $user = $event->user;

        activity()
            ->causedBy($user)
            ->performedOn($user)
            ->withProperties([
                'user_id' => $user->id,
            ])
            ->log('user.logout');
    }
}
