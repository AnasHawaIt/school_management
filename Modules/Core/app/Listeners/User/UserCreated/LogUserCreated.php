<?php

namespace Modules\Core\app\Listeners\User\UserCreated;

use Modules\Core\app\Events\User\UserCreated;

class LogUserCreated
{
    public function handle(UserCreated $event): void
    {
        $user = $event->user;

        activity()
            ->causedBy($event->userId)
            ->performedOn($user)
            ->withProperties([
                'user_id' => $user->id,
            ])
            ->log('user.created');
    }
}
