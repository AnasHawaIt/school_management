<?php

namespace Modules\Library\Listeners\TransactionListeners;

use Modules\Library\Events\BorrowingEvents\BorrowingApproved;

class LogBorrowingApproved
{
    public function handle(BorrowingApproved $event): void
    {
        $borrowing = $event->borrowing;

        activity()
            ->causedBy($event->userId)
            ->performedOn($borrowing)
            ->withProperties([
                'book_id' => $borrowing->book_id,
                'borrowing_id' => $borrowing->id,
            ])
            ->log('borrowing.approved');
    }
}
