<?php


namespace Modules\Library\Listeners\BorrowingListeners\Logs;

use Modules\Library\Events\BorrowingEvents\BookAvailable;
use Modules\Library\Events\BorrowingEvents\BorrowingRenewed;
use Modules\Library\Events\BorrowingEvents\BorrowingUpdated;

class LogBorrowingRenewed
{
    public function handle(BorrowingRenewed $event): void
    {
        $borrowing = $event->borrowing;

        activity()->causedBy(auth()->user())
            ->performedOn($borrowing)
            ->withProperties([
                'book_id' => $borrowing->book_id,
                'borrowing_id' => $borrowing->id,
            ])
            ->log('borrowing.renewed');
    }
}
