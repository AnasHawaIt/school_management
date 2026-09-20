<?php

namespace Modules\Library\app\Listeners\BorrowingListeners;

namespace Modules\Library\app\Listeners\BorrowingListeners\Logs;
use Modules\Library\app\Events\BorrowingEvents\BorrowingReturned;

class LogBorrowingReturned
{
    public function handle(BorrowingReturned $event): void
    {
        $borrowing = $event->borrowing;

        activity()->causedBy(auth()->user())
            ->performedOn($borrowing)
            ->withProperties([
                'book_id' => $borrowing->book_id,
                'borrowing_id' => $borrowing->id,
            ])
            ->log('borrowing.returned');
    }
}

