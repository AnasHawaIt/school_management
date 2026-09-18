<?php


namespace Modules\Messagings\Listeners\Log;

use Modules\Core\app\Entities\User;
use Modules\Messagings\Events\ConversationDeleted;

class LogConversationDeletedEventListener
{
    public function handle(
        ConversationDeleted $event
    ): void
    {

        $user = User::find($event->userId);

        activity()
            ->causedBy($user)
            ->performedOn($event->conversation)
            ->log('Conversation.Deleted');
    }
}
