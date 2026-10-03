<?php


namespace Modules\Messagings\Listeners\Broadcasting;

use Modules\Messagings\Events\AdminDemoted;
use Modules\Messagings\Listeners\Broadcasting\Events\AdminDemotedBroadcast;

class BroadcastAdminDemoted
{
    public function handle(AdminDemoted $event): void
    {
        AdminDemotedBroadcast::dispatch(
            conversationId: $event->conversation->id,
            userId: $event->user->id,
            userName: $event->user->name,
            demotedBy: $event->demotedBy,
        );
    }
}
