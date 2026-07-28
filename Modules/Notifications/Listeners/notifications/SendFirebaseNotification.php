<?php

namespace Modules\Notifications\Listeners\notifications;


use Modules\Notifications\Events\NotificationCreated;
use Modules\Notifications\Services\FirebaseNotificationService;


class SendFirebaseNotification
{


    public function handle(NotificationCreated $event)
    {

        $notification = $event->notification;


        $user = $notification->user;


        if(!$user->fcm_token){
            return;
        }


        app(FirebaseNotificationService::class)
            ->sendFirebase(
                $user->fcm_token,
                $notification->title,
                $notification->body,
                $notification->data ?? []
            );

    }


}
