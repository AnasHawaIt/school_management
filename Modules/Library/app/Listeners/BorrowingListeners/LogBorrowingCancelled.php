<?php


namespace Modules\Library\app\Listeners\BorrowingListeners;

use Modules\Library\app\Events\BorrowingEvents\BorrowingCancelled;
use Modules\Library\app\Events\BorrowingEvents\BorrowingLost;

class LogBorrowingCancelled
{
    public function handle(BorrowingCancelled $event): void
    {
        $borrowing = $event->borrowing;

        activity()
            ->causedBy($event->userId)
            ->performedOn($borrowing)
            ->withProperties([
                'book_id' => $borrowing->book_id,
                'borrowing_id' => $borrowing->id,
            ])
            ->log('borrowing.cancelled');
    }
}
