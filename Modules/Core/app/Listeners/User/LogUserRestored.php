<?php

namespace Modules\Core\app\Listeners\User;

use Modules\Core\app\Events\User\UserRestored;

class LogUserRestored
{
    public function handle(UserRestored $event): void
    {
        $user = $event->user;

        activity()
            ->causedBy($user)
            ->performedOn($user)
            ->withProperties([
                'user_id' => $user->id,
            ])
            ->log('user.restored');
    }
}
