<?php


namespace Modules\Messagings\Listeners\Log;

use Modules\Core\Entities\User;
use Modules\Messagings\Events\ConversationCreated;

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
