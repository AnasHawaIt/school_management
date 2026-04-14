<?php

namespace Modules\Library\Listeners\TransactionListeners;


use Modules\Core\Entities\User;
use Modules\Library\Events\TransactionEvents\TransactionDeleted;
use Modules\Library\Notifications\TransactionDeletedNotification;

class TransactionDeletedNotificationDatabaseListener
{
    public function handle(TransactionDeleted $event)
    {
        $users = User::all();

        foreach ($users as $user) {
            $user->notify(new TransactionDeletedNotification($event->transaction));
        }
    }
}
