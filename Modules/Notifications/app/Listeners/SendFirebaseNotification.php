<?php

namespace Modules\Notifications\app\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Modules\Notifications\app\Events\NotificationCreated;
use Modules\Notifications\app\Services\FirebaseNotificationService;

class SendFirebaseNotification implements ShouldQueue
{
    use InteractsWithQueue;

    public function __construct(
        protected FirebaseNotificationService $firebase
    ) {
    }

    public function handle(NotificationCreated $event): void
    {
        $notification = $event->notification;

        $user = $notification->user;

        if (!$user) {
            return;
        }

        if (!$user->fcm_token) {
            return;
        }

        try {

            $this->firebase->sendFirebase(
                token: $user->fcm_token,
                title: $notification->title,
                body: $notification->body,
                data: $notification->data ?? []
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
