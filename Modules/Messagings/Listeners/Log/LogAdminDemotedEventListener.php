<?php


namespace Modules\Messagings\Listeners\Log;

use Modules\Core\Entities\User;
use Modules\Messagings\Events\AdminDemoted;

class LogAdminDemotedEventListener
{
    public function handle(
        AdminDemoted $event
    ): void
    {

        $user = User::find($event->demotedBy);

        activity()
            ->causedBy($user)
            ->performedOn($event->conversation)
            ->withProperties([
                'demoted_user_id' => $event->user->id,
            ])
            ->log('Conversation.AdminDemoted');
    }
}
