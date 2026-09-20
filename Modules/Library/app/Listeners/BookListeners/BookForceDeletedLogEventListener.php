<?php

namespace Modules\Library\Listeners\BookListeners;

use Modules\Library\Events\BookEvents\BookForceDeleted;

class BookForceDeletedLogEventListener
{
    public function handle(BookForceDeleted $event)
    {
        $book = $event->book;

        activity()
            ->causedBy(auth()->user())
            ->performedOn($book)
            ->withProperties([
                'book_id' => $book->id,
            ])
            ->log('book.forceDeleted');
    }
}
