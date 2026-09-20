<?php


<<<<<<<< HEAD:Modules/Library/app/Listeners/BorrowingListeners/LogBorrowingLost.php
namespace Modules\Library\app\Listeners\BorrowingListeners;
========
namespace Modules\Library\Listeners\BorrowingListeners\Logs;
>>>>>>>> 805201b1233d594b84aa4e236cb851d59081984d:Modules/Library/app/Listeners/BorrowingListeners/Logs/LogBorrowingLost.php

use Modules\Library\app\Events\BorrowingEvents\BorrowingLost;

class LogBorrowingLost
{
    public function handle(BorrowingLost $event): void
    {
        $borrowing = $event->borrowing;

        activity()->causedBy(auth()->user())
            ->performedOn($borrowing)
            ->withProperties([
                'book_id' => $borrowing->book_id,
                'borrowing_id' => $borrowing->id,
            ])
            ->log('borrowing.lost');
    }
}
