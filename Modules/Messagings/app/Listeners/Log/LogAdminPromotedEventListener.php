<?php


namespace Modules\Messagings\app\Listeners\Log;

use Modules\Core\app\Entities\User;
use Modules\Messagings\app\Events\AdminPromoted;

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
