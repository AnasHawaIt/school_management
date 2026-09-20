<?php


namespace Modules\Messagings\app\Listeners\Broadcasting;

use Modules\Messagings\app\Events\AdminPromoted;
use Modules\Messagings\app\Listeners\Broadcasting\Events\AdminPromotedBroadcast;

class BroadcastAdminPromoted
{
    public function handle(AdminPromoted $event): void
    {
        AdminPromotedBroadcast::dispatch(
            conversationId: $event->conversation->id,
            userId: $event->user->id,
            userName: $event->user->name,
            promotedBy: $event->promotedBy,
        );
    }
}
