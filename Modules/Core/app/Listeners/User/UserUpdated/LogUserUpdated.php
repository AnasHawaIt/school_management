<?php

namespace Modules\Core\app\Listeners\User\UserUpdated;

use Modules\Core\app\Events\User\UserUpdated;

class LogUserUpdated
{
    public function handle(UserUpdated $event): void
    {
        $user = $event->user;

        activity()
            ->causedBy($event->userId)
            ->performedOn($user)
            ->withProperties([
                'user_id' => $user->id,
            ])
            ->log('user.updated');
    }
}
