<?php


namespace Modules\Messagings\app\Listeners\Broadcasting;

use Modules\Messagings\app\Events\AdminDemoted;
use Modules\Messagings\app\Listeners\Broadcasting\Events\AdminDemotedBroadcast;

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
