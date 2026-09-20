<?php


namespace Modules\Messagings\app\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Modules\Messagings\app\Entities\Conversation;

class ConversationCreated
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public Conversation $conversation,
        public int          $userId
    )
    {
    }
}
