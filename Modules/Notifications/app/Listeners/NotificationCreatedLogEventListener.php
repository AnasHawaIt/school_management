<?php

namespace Modules\Notifications\Listeners;

use Modules\Notifications\Events\NotificationCreated;

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
