<?php


namespace Modules\Messagings\app\Policies;

use Modules\Core\Entities\User;
use Modules\Messagings\Entities\Conversation;
use Modules\Messagings\Entities\MessageAttachment;

class MessageAttachmentPolicy
{
    /**
     * View attachment
     */
    public function view(
        User              $user,
        MessageAttachment $attachment
    ): bool
    {
        return $user->can(
            'view',
            $attachment->message
        );
    }

    /**
     * Delete attachment
     */
    public function delete(
        User $user,
        MessageAttachment $attachment,
        Conversation $conversation
    ): bool {
        // System admin
                if ($user->hasRole('admin')) {
                    return true;
                }

        // Conversation owner or admin
        if (
            $conversation->isOwner($user->id)
            || $conversation->isAdmin($user->id)
        ) {
            return true;
        }

        // Otherwise check whether the user can delete the message
        return $user->can(
            'delete',
            $attachment->message
        );
    }

}
