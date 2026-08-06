<?php

namespace Modules\Notifications\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Modules\Notifications\Events\NotificationCreated;
use Modules\Notifications\Services\FirebaseNotificationService;

class SendFirebaseNotification implements ShouldQueue
{
    use InteractsWithQueue;

    public function __construct(
        protected FirebaseNotificationService $firebase
    ) {}

    public function handle(NotificationCreated $event): void
    {
        $notification = $event->notification;

        $user = $notification->user;

        if (!$user || !$user->fcm_token) {

            $notification->update([
                'status' => 'failed',
                'error_message' => 'User does not have FCM token.'
            ]);

            return;
        }

        try {

            $this->firebase->sendFirebase(
                $user->fcm_token,
                $notification->title,
                $notification->body,
                $notification->data ?? []
            );

            $notification->update([
                'status' => 'sent',
                'sent_at' => now(),
                'error_message' => null,
            ]);

        } catch (\Throwable $e) {

            $notification->update([
                'status' => 'failed',
                'error_message' => $e->getMessage(),
            ]);

            throw $e;
        }
    }
}
