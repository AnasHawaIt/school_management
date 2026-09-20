<?php


namespace Modules\Messagings\app\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Modules\Core\app\Entities\User;
use Modules\Messagings\app\Entities\Conversation;

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
