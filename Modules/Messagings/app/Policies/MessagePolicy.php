<?php

namespace Modules\Messagings\app\Policies;

use Modules\Core\Entities\User;
use Modules\Messagings\Entities\Message;
use Modules\Messagings\Entities\MessageRecipient;

class MessagePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasRole('Admin')
            || $user->hasRole('Principal');
    }



    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Message $message): bool
    {
        if (
            $user->hasRole('Admin')
            || $user->hasRole('Principal')
        ) {
            return true;
        }

        if ($message->sender_id == $user->id) {
            return true;
        }

        return MessageRecipient::query()
            ->where('message_id', $message->id)
            ->where('recipient_id', $user->id)
            ->exists();
    }

    public function send(User $user): bool
    {
        return $user->hasAnyRole([
            'Admin',
            'Principal',
            'Teacher',
            'Employee',
            'Student',
            'Parent'
        ]);
    }


    public function reply(User $user, Message $message): bool
    {
        return $this->view($user, $message);
    }

    public function forward(User $user, Message $message): bool
    {
        if (
            $user->hasRole('Admin')
            || $user->hasRole('Principal')
        ) {
            return true;
        }

        if ($message->sender_id == $user->id) {
            return true;
        }

        return MessageRecipient::query()
            ->where('message_id', $message->id)
            ->where('recipient_id', $user->id)
            ->exists();
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Message $message): bool
    {
        if (
            $user->hasRole('Admin')
            || $user->hasRole('Principal')
        ) {
            return true;
        }

        return $message->sender_id == $user->id;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Message $message): bool
    {
        return $user->hasRole('Admin')
            || $user->hasRole('Principal');

    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Message $message): bool
    {
        return $user->hasRole('Admin');
    }
}
