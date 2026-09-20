<?php


<<<<<<<< HEAD:Modules/Library/app/Listeners/BorrowingListeners/LogBorrowingOverdue.php
namespace Modules\Library\app\Listeners\BorrowingListeners;

use Modules\Library\app\Events\BorrowingEvents\BorrowingOverdue;
use Modules\Library\app\Events\BorrowingEvents\BorrowingRejected;
========
namespace Modules\Library\Listeners\BorrowingListeners\Logs;

use Modules\Library\Events\BorrowingEvents\BorrowingOverdue;
>>>>>>>> 805201b1233d594b84aa4e236cb851d59081984d:Modules/Library/app/Listeners/BorrowingListeners/Logs/LogBorrowingOverdue.php

class LogBorrowingOverdue
{
    public function handle(BorrowingOverdue $event): void
    {
        $borrowing = $event->borrowing;

        activity()
            ->causedBy(auth()->user())
            ->performedOn($borrowing)
            ->withProperties([
                'book_id' => $borrowing->book_id,
                'borrowing_id' => $borrowing->id,
            ])
            ->log('borrowing.overdue');
    }
}
