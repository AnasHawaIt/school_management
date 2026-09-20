<?php

namespace Modules\Core\app\Listeners\Auth;

use Modules\Core\app\Events\Auth\TokenRefreshed;


class LogTokenRefreshed
{
    public function handle(TokenRefreshed $event): void
    {
        activity()
            ->causedBy($event->user)
            ->performedOn($event->user)
            ->withProperties([
                'user_id' => $event->userId ?? $event->user->id,
            ])
            ->log('user.token_refreshed');
    }
}
