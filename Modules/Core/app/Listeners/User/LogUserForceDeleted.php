<?php

namespace Modules\Core\Listeners\User;

use Modules\Core\Events\User\UserForceDeleted;

class LogUserForceDeleted
{
    public function handle(UserForceDeleted $event): void
    {
        $user = $event->user;

        activity()
            ->causedBy($user)
            ->performedOn($user)
            ->withProperties([
                'user_id' => $user->id,
            ])
            ->log('user.deleted');
    }
}
