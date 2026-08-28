<?php


namespace Modules\Messagings\app\Policies;

use Modules\Core\Entities\User;
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
        User              $user,
        MessageAttachment $attachment
    ): bool
    {
        return $user->can(
            'delete',
            $attachment->message
        );
    }
}
