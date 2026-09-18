<?php

namespace Modules\Core\app\Listeners\User;

use Modules\Core\app\Events\User\UserForceDeleted;

class LogUserForceDeleted
{
    public function handle(UserForceDeleted $event): void
    {
        $user = $event->user;

        activity()
            ->causedBy($event->userId)
            ->performedOn($user)
            ->withProperties([
                'user_id' => $user->id,
            ])
            ->log('user.deleted');
    }
}
