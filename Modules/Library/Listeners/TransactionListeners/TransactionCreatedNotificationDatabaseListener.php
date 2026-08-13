<?php

namespace Modules\Library\Listeners\TransactionListeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Core\Entities\User;
use Modules\Library\Events\BorrowingEvents\BorrowingCreated;
use Modules\Library\Notifications\TransactionCreatedNotification;

class TransactionCreatedNotificationDatabaseListener implements ShouldQueue
{
    public function handle(BorrowingCreated $event): void
    {


        $query = User::query();

        $users = $query->get();

        foreach ($users as $user) {
            $user->notify(
                new TransactionCreatedNotification(
                    $event->borrowing
                )
            );
        }
    }
}
