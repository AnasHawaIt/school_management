<?php

namespace Modules\Core\Listeners\User\UserDeleted;

use Modules\Core\Events\User\UserDeleted;

class LogUserDeleted
{
    public function handle(UserDeleted $event): void
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
