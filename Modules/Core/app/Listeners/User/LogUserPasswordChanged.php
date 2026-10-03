<?php

namespace Modules\Core\Listeners\User;

use Modules\Core\Events\User\UserPasswordChanged;

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
