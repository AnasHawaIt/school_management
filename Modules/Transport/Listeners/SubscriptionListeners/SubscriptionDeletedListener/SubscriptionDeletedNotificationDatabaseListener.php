<?php

namespace Modules\Transport\Listeners\SubscriptionListeners\SubscriptionDeletedListener;



use Illuminate\Support\Facades\Notification;
use Modules\Core\Entities\User;
use Modules\Transport\Events\SubscriptionEvents\TransactionDeleted;
use Modules\Transport\Notifications\SubscriptionCreatedNotification;
use Modules\Transport\Notifications\SubscriptionDeletedNotification;

class SubscriptionDeletedNotificationDatabaseListener
{
    public function handle(TransactionDeleted $event)
    {
        $users = User::all();

        foreach ($users as $user) {
            $user->notify(new SubscriptionDeletedNotification($event->subscription));
        }
    }
}
