<?php

namespace Modules\Library\Listeners\BookListeners;

use Modules\Library\Events\BookEvents\BookRestored;
use Modules\Library\Events\BorrowingEvents\BorrowingRestored;

class BookRestoredLogEventListener
{
    public function handle(BookRestored $event)
    {
        $book = $event->book;

        activity()
            ->causedBy(auth()->user())
            ->performedOn($book)
            ->withProperties([
                'book_id' => $book->id,
            ])
            ->log('book.restored');
    }
}
