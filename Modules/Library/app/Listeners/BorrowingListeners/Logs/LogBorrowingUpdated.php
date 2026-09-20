<?php


namespace Modules\Library\app\Listeners\BorrowingListeners\Logs;

use Modules\Library\app\Events\BorrowingEvents\BorrowingUpdated;

class LogBorrowingUpdated
{
    public function handle(BorrowingUpdated $event): void
    {
        $borrowing = $event->borrowing;

        activity()->causedBy(auth()->user())
            ->performedOn($borrowing)
            ->withProperties([
                'book_id' => $borrowing->book_id,
                'borrowing_id' => $borrowing->id,
            ])
            ->log('borrowing.updated');
    }
}
