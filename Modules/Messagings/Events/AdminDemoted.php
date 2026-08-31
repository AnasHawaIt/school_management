<?php


namespace Modules\Messagings\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Modules\Messagings\Entities\Conversation;
use Modules\Core\Entities\User;

class AdminDemoted
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public Conversation $conversation,
        public User         $user,
        public int          $demotedBy
    )
    {
    }
}
