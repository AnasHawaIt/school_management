<?php

namespace Modules\Core\app\Listeners\Auth;

use Modules\Core\app\Events\Auth\TokenRefreshed;


class LogTokenRefreshed
{
    public function handle(TokenRefreshed $event): void
    {
        $user = $event->user;

        activity()
            ->causedBy($event->userId)
            ->performedOn($user)
            ->withProperties([
                'user_id' => $user->id,
            ])
            ->log('user.token_refreshed');
    }
}
