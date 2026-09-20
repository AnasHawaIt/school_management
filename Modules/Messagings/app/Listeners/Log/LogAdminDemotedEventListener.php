<?php


namespace Modules\Messagings\app\Listeners\Log;

use Modules\Core\app\Entities\User;
use Modules\Messagings\app\Events\AdminDemoted;

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
