<?php

namespace Modules\Library\Listeners\TransactionListeners;


use Modules\Core\Entities\User;
use Modules\Library\Events\TransactionEvents\TransactionUpdated;
use Modules\Library\Notifications\TransactionUpdateNotification;

class TransactionUpdatedNotificationDatabaseListener
{
    public function handle(TransactionUpdated $event)
    {
        $users = User::all();

        foreach ($users as $user) {
            $user->notify(new TransactionUpdateNotification($event->transaction));
        }
    }
}
