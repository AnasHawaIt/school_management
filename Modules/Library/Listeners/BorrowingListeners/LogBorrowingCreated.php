<?php


namespace Modules\Library\Listeners\BorrowingListeners;

use Modules\Library\Events\BorrowingEvents\BorrowingCreated;

class LogBorrowingCreated
{
    public function handle(BorrowingCreated $event): void
    {
        $borrowing = $event->borrowing;

        activity()
            ->performedOn($borrowing)
            ->withProperties([
                'book_id' => $borrowing->book_id,
                'borrowing_id' => $borrowing->id,
            ])
            ->log('borrowing.created');
    }
}
