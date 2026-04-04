<?php

namespace Modules\Transport\Listeners\SubscriptionListeners\SubscriptionCreatedListener;

use Illuminate\Support\Facades\Notification;
use Modules\Core\Entities\User;
use Modules\Transport\Events\SubscriptionEvents\SubscriptionCreated;
use Modules\Transport\Notifications\SubscriptionCreatedNotification;

class SubscriptionCreatedNotificationDatabaseListener
{
    public function handle(SubscriptionCreated $event)
    {
        $users = User::all();

        foreach ($users as $user) {
            $user->notify(new SubscriptionCreatedNotification($event->subscription));
        }
    }
}
