<?php

namespace Modules\Core\app\Listeners\User\UserDeleted;

use Modules\Core\app\Events\Broadcasted\UserDeletedBroadcasted;
use Modules\Core\app\Events\User\UserDeleted;

class BroadcastUserDeleted
{
    public function handle(UserDeleted $event): void
    {
        event(new UserDeletedBroadcasted(
            user: $event->user,
            userId: $event->userId,
        ));
    }
}
