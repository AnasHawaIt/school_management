<?php


namespace Modules\Messagings\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Modules\Messagings\Entities\Conversation;
use Modules\Core\Entities\User;

class ParticipantLeft
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public Conversation $conversation,
        public User         $user
    )
    {
    }
}
