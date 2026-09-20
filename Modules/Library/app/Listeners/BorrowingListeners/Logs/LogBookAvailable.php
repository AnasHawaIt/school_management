<?php


namespace Modules\Library\app\Listeners\BorrowingListeners\Logs;

use Modules\Library\app\Events\BorrowingEvents\BookAvailable;

class LogBookAvailable
{
    public function handle(BookAvailable $event): void
    {
        activity()->causedBy(auth()->user())
            ->performedOn($event->book)
            ->withProperties([
                'book_id' => $event->book->id,
                'book' => $event->book
            ])
            ->log('Book.available');
    }
}
