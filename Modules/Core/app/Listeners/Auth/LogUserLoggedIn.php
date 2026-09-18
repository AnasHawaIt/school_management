<?php

namespace Modules\Core\app\Listeners\Auth;


use Modules\Core\app\Events\Auth\UserLoggedIn;

class LogUserLoggedIn{
    public function handle(UserLoggedIn $event): void
    {
        $user = $event->user;

        activity()
            ->causedBy($event->userId)
            ->performedOn($user)
            ->withProperties([
                'user_id' => $user->id,
            ])
            ->log('user.login');
    }
}
