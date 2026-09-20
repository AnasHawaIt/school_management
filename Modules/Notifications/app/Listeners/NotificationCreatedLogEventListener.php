<?php

namespace Modules\Notifications\app\Listeners;

use Modules\Notifications\app\Events\NotificationCreated;

class NotificationCreatedLogEventListener
{
    public function handle(NotificationCreated $event)
    {
        $notification = $event->notification;

        activity()
            ->causedBy(auth()->user())
            ->performedOn($notification)
            ->withProperties([
                'Notification_id' => $notification->id,
            ])
            ->log('Notification.Created');
    }
}
