<?php

namespace Modules\Messagings\app\Policies;

use Modules\Core\Entities\User;
use Modules\Messagings\Entities\Conversation;

class ConversationPolicy
{

    public function view(User $user, Conversation $conversation): bool
    {
        return $this->isParticipant($user, $conversation);
    }

    public function send(User $user, Conversation $conversation): bool
    {
        return $this->isParticipant($user, $conversation);
    }

    public function reply(User $user, Conversation $conversation): bool
    {
        return $this->isParticipant($user, $conversation);
    }


    public function addParticipant(
        User $user,
        Conversation $conversation
    ): bool {
        return $user->hasRole('admin');
    }

    public function leave(
        User $user,
        Conversation $conversation
    ): bool {
        return $this->isParticipant($user, $conversation);
    }

    public function removeParticipant(
        User $user,
        Conversation $conversation
    ): bool {
        return $user->hasRole('admin');
    }

    public function delete(
        User $user,
        Conversation $conversation
    ): bool {
        return $user->hasRole('admin');
    }

    public function addAdmin(
        User $user,
        Conversation $conversation
    ): bool {
        return $conversation->participants()
            ->where('user_id', $user->id)
            ->where('conversation_Role', 'owner')
            ->exists();
    }

    public function removeAdmin(
        User $user,
        Conversation $conversation
    ): bool {
        return $conversation->participants()
            ->where('user_id', $user->id)
            ->where('conversation_Role', 'owner')
            ->exists();
    }


    protected function isParticipant(
        User $user,
        Conversation $conversation
    ): bool {
        return $conversation->participants()
            ->where('user_id', $user->id)
            ->exists();
    }
}
