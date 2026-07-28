<?php

namespace Modules\Library\Listeners\TransactionListeners;


use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Core\Entities\User;
use Modules\Library\Events\BorrowingEvents\BorrowingCreated;
use Modules\Library\Notifications\CreateBorrowingApprovedNotification;

class TransactionCreatedNotificationDatabaseListener  implements ShouldQueue
{
    public function handle(BorrowingCreated $event)
    {
        $users = User::all();

        foreach ($users as $user) {
            $user->notify(new CreateBorrowingApprovedNotification($event->transaction));
        }
    }
}
