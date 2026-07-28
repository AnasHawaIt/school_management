<?php

namespace Modules\Library\Listeners\BookListeners\BookCreatedListener;

use Modules\Library\Events\BookEvents\BookCreated;

class BookCreatedLogEventListener
{
    public function handle(BookCreated $event)
    {
        $book = $event->book;

        activity()
            ->causedBy($event->userId)
            ->performedOn($book)
            ->withProperties([
                'book_id' => $book->id,
            ])
            ->log('book.created');
    }
}
