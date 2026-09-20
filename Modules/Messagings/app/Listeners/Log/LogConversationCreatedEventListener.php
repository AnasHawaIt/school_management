<?php


namespace Modules\Messagings\app\Listeners\Log;

use Modules\Core\app\Entities\User;
use Modules\Messagings\app\Events\ConversationCreated;

class LogConversationCreatedEventListener
{
    public function handle(
        ConversationCreated $event
    ): void
    {

        $user = User::find($event->userId);

        activity()
            ->causedBy($user)
            ->performedOn($event->conversation)
            ->log('Conversation.Created');
    }
}
