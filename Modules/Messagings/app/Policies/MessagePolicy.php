<?php

namespace Modules\Messagings\app\Policies;

use Modules\Core\Entities\User;
use Modules\Messagings\Entities\Message;

class MessagePolicy
{

    public function viewTrashed(User $user): bool
    {
        return $user->hasRole('admin');
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
        Message $message
    ): bool {
        if ($user->hasRole('admin')) {
            return true;
        }

        return $message->sender_id === $user->id;
    }

    public function restore(
        User $user,
        Message $message
    ): bool {
        return $user->hasRole('admin');
    }

    public function forceDelete(
        User $user,
        Message $message
    ): bool {
        return $user->hasRole('admin');
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
