<?php

namespace Modules\Transport\Listeners\SubscriptionListeners\SubscriptionCreatedListener;

use Illuminate\Support\Facades\Notification;
use Modules\Core\Entities\User;
use Modules\Transport\Events\SubscriptionEvents\TransactionCreated;
use Modules\Transport\Notifications\SubscriptionCreatedNotification;

class SubscriptionCreatedNotificationDatabaseListener
{
    public function handle(TransactionCreated $event)
    {
        $users = User::all();

        foreach ($users as $user) {
            $user->notify(new SubscriptionCreatedNotification($event->subscription));
        }
    }
}
