<?php

namespace Modules\Core\app\Listeners\User\UserCreated;

use Modules\Core\app\Events\User\UserCreated;

class LogUserCreated
{
    public function handle(UserCreated $event): void
    {
        $user = $event->user;

        activity()
            ->causedBy($user)
            ->performedOn($user)
            ->withProperties([
                'user_id' => $user->id,
            ])
            ->log('user.created');
    }
}
