<?php

namespace Modules\Library\Listeners\BorrowingListeners;

use Modules\Library\Events\BorrowingEvents\BorrowingPickedUp;

class LogBorrowingPickedUp
{
    public function handle(BorrowingPickedUp $event): void
    {
        $borrowing = $event->borrowing;

        activity()
            ->causedBy($event->userId)
            ->performedOn($borrowing)
            ->withProperties([
                'book_id' => $borrowing->book_id,
                'borrowing_id' => $borrowing->id,
            ])
            ->log('borrowing.pickedUp');
    }
}
