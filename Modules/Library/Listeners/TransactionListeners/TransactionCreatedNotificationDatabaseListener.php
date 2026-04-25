<?php

namespace Modules\Library\Listeners\TransactionListeners;


use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Core\Entities\User;
use Modules\Library\Events\TransactionEvents\TransactionCreated;
use Modules\Library\Notifications\TransactionCreatedNotification;

class TransactionCreatedNotificationDatabaseListener  implements ShouldQueue
{
    public function handle(TransactionCreated $event)
    {
        $users = User::all();

        foreach ($users as $user) {
            $user->notify(new TransactionCreatedNotification($event->transaction));
        }
    }
}
