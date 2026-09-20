<?php

namespace Modules\Library\app\Listeners\BorrowingListeners;

namespace Modules\Library\app\Listeners\BorrowingListeners\Logs;

use Modules\Library\app\Events\BorrowingEvents\BorrowingCancelled;

class LogBorrowingCancelled
{
    public function handle(BorrowingCancelled $event): void
    {
        $borrowing = $event->borrowing;

        activity()->causedBy(auth()->user())
            ->performedOn($borrowing)
            ->withProperties([
                'book_id' => $borrowing->book_id,
                'borrowing_id' => $borrowing->id,
            ])
            ->log('borrowing.cancelled');
    }
}
