<?php

namespace Modules\Messagings\app\Policies;

use Modules\Core\Entities\User;
use Modules\Messagings\Entities\Conversation;

class ConversationPolicy
{
    public function view(
        User $user,
        Conversation $conversation
    ): bool {
        return $conversation->isParticipant($user->id);
    }

    public function send(
        User $user,
        Conversation $conversation
    ): bool {
        return $conversation->isParticipant($user->id);
    }

    public function reply(
        User $user,
        Conversation $conversation
    ): bool {
        return $this->isParticipant($user, $conversation);
    }

    /*
    |--------------------------------------------------------------------------
    | Owner
    |--------------------------------------------------------------------------
    */

    public function isOwner(
        User $user,
        Conversation $conversation
    ): bool {
        return $conversation->isOwner($user->id);
    }

    /*
    |--------------------------------------------------------------------------
    | Add Participant
    |--------------------------------------------------------------------------
    */

    public function addParticipant(
        User $user,
        Conversation $conversation
    ): bool {
        return $user->hasRole('admin')
            || $conversation->isOwner($user->id);
    }

    /*
    |--------------------------------------------------------------------------
    | Remove Participant
    |--------------------------------------------------------------------------
    */

    public function removeParticipant(
        User $user,
        Conversation $conversation
    ): bool {
        return $user->hasRole('admin')
            || $conversation->isOwner($user->id);
    }

    /*
    |--------------------------------------------------------------------------
    | Add Admin
    |--------------------------------------------------------------------------
    */

    public function addAdmin(
        User $user,
        Conversation $conversation
    ): bool {
        return $conversation->isOwner($user->id);
    }

    /*
    |--------------------------------------------------------------------------
    | Remove Admin
    |--------------------------------------------------------------------------
    */

    public function removeAdmin(
        User $user,
        Conversation $conversation
    ): bool {
        return $conversation->isOwner($user->id);
    }

    /*
    |--------------------------------------------------------------------------
    | Leave
    |--------------------------------------------------------------------------
    */

    public function leave(
        User $user,
        Conversation $conversation
    ): bool {
        return $conversation->isParticipant($user->id)
            && !$conversation->isOwner($user->id);
    }

    /*
    |--------------------------------------------------------------------------
    | Delete
    |--------------------------------------------------------------------------
    */

    public function delete(
        User $user,
        Conversation $conversation
    ): bool {
        return $user->hasRole('admin')
            || $conversation->isOwner($user->id);
    }

    /*
    |--------------------------------------------------------------------------
    | Participant Check
    |--------------------------------------------------------------------------
    */

    protected function isParticipant(
        User $user,
        Conversation $conversation
    ): bool {
        return $conversation->participants()
            ->where('users.id', $user->id)
            ->exists();
    }
}
