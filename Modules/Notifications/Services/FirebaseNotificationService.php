<?php

namespace Modules\Notifications\Services;

use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification as FirebaseNotification;
use Modules\Notifications\Entities\Notification;

class FirebaseNotificationService
{
    public function resend(int $id): Notification
    {
        $notification = $this->find($id);

        $token = 'ضع هنا FCM Token حقيقي';

        app(FirebaseNotificationService::class)
            ->sendFirebase(
                $token,
                $notification->title,
                $notification->body,
                $notification->data ?? []
            );

        return $notification;
    }
}
