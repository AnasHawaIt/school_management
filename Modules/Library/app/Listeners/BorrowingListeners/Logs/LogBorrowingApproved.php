<?php

<<<<<<<< HEAD:Modules/Library/app/Listeners/BorrowingListeners/LogBorrowingApproved.php
namespace Modules\Library\app\Listeners\BorrowingListeners;
========
namespace Modules\Library\Listeners\BorrowingListeners\Logs;
>>>>>>>> 805201b1233d594b84aa4e236cb851d59081984d:Modules/Library/app/Listeners/BorrowingListeners/Logs/LogBorrowingApproved.php

use Modules\Library\app\Events\BorrowingEvents\BorrowingApproved;

class LogBorrowingApproved
{
    public function handle(BorrowingApproved $event): void
    {
        $borrowing = $event->borrowing;

        activity()->causedBy(auth()->user())
            ->performedOn($borrowing)
            ->withProperties([
                'book_id' => $borrowing->book_id,
                'borrowing_id' => $borrowing->id,
            ])
            ->log('borrowing.approved');
    }
}
