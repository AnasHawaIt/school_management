<?php

namespace Modules\Transport\Listeners\SubscriptionListeners\SubscriptionDeletedListener;

use Modules\Core\Entities\User;
use Modules\Transport\Events\SubscriptionEvents\SubscriptionDeleted;
use Modules\Transport\Notifications\SubscriptionDeletedNotification;

class SubscriptionDeletedNotificationDatabaseListener
{
    public function handle(SubscriptionDeleted $event)
    {
        $users = User::all();

        foreach ($users as $user) {
            $user->notify(new SubscriptionDeletedNotification($event->subscription));
        }
    }
}
