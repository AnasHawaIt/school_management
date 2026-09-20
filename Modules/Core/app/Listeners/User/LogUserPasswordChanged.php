<?php

namespace Modules\Core\app\Listeners\User;

use Modules\Core\app\Events\User\UserPasswordChanged;

class LogUserPasswordChanged
{
    public function handle(UserPasswordChanged $event): void
    {
        $user = $event->user;

        activity()
            ->causedBy($user)
            ->performedOn($user)
            ->withProperties([
                'user_id' => $user->id,
            ])
            ->log('user.password_changed');
    }
}
