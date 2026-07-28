<?php

namespace Modules\Library\Listeners\BookListeners\BookDeletedListener;

use Modules\Library\Events\BookEvents\BookDeleted;

class BookDeletedLogEventListener
{
    public function handle(BookDeleted $event)
    {
        $book = $event->book;

        activity()
            ->causedBy($event->userId)
            ->performedOn($book)
            ->withProperties([
                'book_id' => $book->id,
            ])
            ->log('book.deleted');
    }
}
