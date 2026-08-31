<?php


namespace Modules\Messagings\Listeners\Log;

use Modules\Core\Entities\User;
use Modules\Messagings\Events\AdminPromoted;

class LogAdminPromotedEventListener
{
    public function handle(
        AdminPromoted $event
    ): void
    {

        $user = User::find($event->promotedBy);

        activity()
            ->causedBy($user)
            ->performedOn($event->conversation)
            ->withProperties([
                'promoted_user_id' => $event->user->id,
            ])
            ->log('Conversation.AdminPromoted');
    }
}
