<?php


<<<<<<<< HEAD:Modules/Library/app/Listeners/BorrowingListeners/LogBorrowingCreated.php
namespace Modules\Library\app\Listeners\BorrowingListeners;

use Modules\Library\app\Events\BorrowingEvents\BorrowingRejected;
========
namespace Modules\Library\Listeners\BorrowingListeners\Logs;

use Modules\Library\Events\BorrowingEvents\BorrowingCreated;
>>>>>>>> 805201b1233d594b84aa4e236cb851d59081984d:Modules/Library/app/Listeners/BorrowingListeners/Logs/LogBorrowingCreated.php

class LogBorrowingCreated
{
    public function handle(BorrowingCreated $event): void
    {
        $borrowing = $event->borrowing;

        activity()->causedBy(auth()->user())
            ->performedOn($borrowing)
            ->withProperties([
                'book_id' => $borrowing->book_id,
                'borrowing_id' => $borrowing->id,
            ])
            ->log('borrowing.created');
    }
}
