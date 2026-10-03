<?php

namespace Modules\Core\Listeners\User\UserUpdated;

use Modules\Core\Events\User\UserUpdated;

class LogUserUpdated
{
    public function handle(UserUpdated $event): void
    {
        $user = $event->user;

        activity()
            ->causedBy($user)
            ->performedOn($user)
            ->withProperties([
                'user_id' => $user->id,
            ])
            ->log('user.updated');
    }
}
