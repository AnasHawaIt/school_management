<?php

namespace Modules\Core\app\Listeners\User\UserDeleted;

use Modules\Core\app\Events\User\UserDeleted;

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
