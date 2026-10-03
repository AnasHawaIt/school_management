<?php


namespace Modules\Messagings\Listeners\Broadcasting;

use Modules\Messagings\Events\AdminPromoted;
use Modules\Messagings\Listeners\Broadcasting\Events\AdminPromotedBroadcast;

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
