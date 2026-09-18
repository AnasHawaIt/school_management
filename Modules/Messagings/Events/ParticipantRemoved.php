<?php


namespace Modules\Messagings\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Modules\Core\app\Entities\User;
use Modules\Messagings\Entities\Conversation;

class ParticipantRemoved
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public Conversation $conversation,
        public User         $user,
        public int          $removedBy
    )
    {
    }
}
