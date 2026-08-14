<?php


namespace Modules\Library\Listeners\BorrowingListeners;

use Modules\Library\Events\BorrowingEvents\BorrowingCancelled;
use Modules\Library\Events\BorrowingEvents\BorrowingLost;
use Modules\Library\Events\BorrowingEvents\BorrowingUpdateed;

class LogBorrowingUpdated
{
    public function handle(BorrowingUpdateed $event): void
    {
        $borrowing = $event->borrowing;

        activity()
            ->causedBy($event->userId)
            ->performedOn($borrowing)
            ->withProperties([
                'book_id' => $borrowing->book_id,
                'borrowing_id' => $borrowing->id,
            ])
            ->log('borrowing.updated');
    }
}
