<?php

namespace Modules\Library\app\Listeners\BookListeners\BookDeletedListener;

use Modules\Library\app\Events\BookEvents\BookDeleted;

class BookDeletedLogEventListener
{
    public function handle(BookDeleted $event)
    {
        $book = $event->book;

        activity()
            ->causedBy(auth()->user())
            ->performedOn($book)
            ->withProperties([
                'book_id' => $book->id,
            ])
            ->log('book.deleted');
    }
}
