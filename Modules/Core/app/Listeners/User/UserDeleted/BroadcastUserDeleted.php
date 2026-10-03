<?php

namespace Modules\Core\Listeners\User\UserDeleted;

use Modules\Core\Events\Broadcasted\UserDeletedBroadcasted;
use Modules\Core\Events\User\UserDeleted;

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
