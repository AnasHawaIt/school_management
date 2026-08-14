<?php


namespace Modules\Library\Listeners\BorrowingListeners;

use Modules\Library\Events\BorrowingEvents\BorrowingOverdue;
use Modules\Library\Events\BorrowingEvents\BorrowingRejected;

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
