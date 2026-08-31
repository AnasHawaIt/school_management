<?php

namespace Modules\Messagings\app\Policies;

use Modules\Core\Entities\User;
use Modules\Messagings\Entities\Conversation;
use Modules\Messagings\Entities\Message;

class MessagePolicy
{

    public function viewTrashed(
    User $user,
    Conversation $conversation
): bool {
    return $user->hasRole('admin')
        || $conversation->isOwner($user->id)||$conversation->isAdmin($user->id);
}

    public function view(User $user, Message $message): bool
    {
        return $this->isConversationParticipant(
            $user,
            $message
        );
    }

    public function markAsRead(
        User $user,
        Message $message
    ): bool {
        return $this->isConversationParticipant(
            $user,
            $message
        );
    }

    public function reply(
        User $user,
        Message $message
    ): bool {
        return $this->isConversationParticipant(
            $user,
            $message
        );
    }


    public function forward(
        User $user,
        Message $message
    ): bool {
        return $this->isConversationParticipant(
            $user,
            $message
        );
    }


    public function delete(
        User $user,
        Message $message,
        Conversation $conversation
    ): bool {
        return $user->hasRole('admin')
            || $conversation->isOwner($user->id)
            || $conversation->isAdmin($user->id);
    }

    public function restore(
        User $user,
        Message $message,
        Conversation $conversation
    ): bool {
        return $user->hasRole('admin')
            || $conversation->isOwner($user->id)
            || $conversation->isAdmin($user->id);
    }

    public function forceDelete(
        User $user,
        Message $message,
        Conversation $conversation
    ): bool {
        return $user->hasRole('admin')
            || $conversation->isOwner($user->id)
            || $conversation->isAdmin($user->id);
    }

    protected function isConversationParticipant(
        User $user,
        Message $message
    ): bool {
        return $message->conversation()
            ->whereHas('participants', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->exists();
    }
}
