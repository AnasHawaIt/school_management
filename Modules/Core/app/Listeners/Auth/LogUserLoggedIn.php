<?php

namespace Modules\Core\Listeners\Auth;


use Modules\Core\Events\Auth\UserLoggedIn;

class LogUserLoggedIn{
    public function handle(UserLoggedIn $event): void
    {
        $user = $event->user;

        activity()
            ->causedBy($user)
            ->performedOn($user)
            ->withProperties([
                'user_id' => $user->id,
            ])
            ->log('user.login');
    }
}
