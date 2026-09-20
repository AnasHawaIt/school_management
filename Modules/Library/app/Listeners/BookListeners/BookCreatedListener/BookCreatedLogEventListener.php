<?php

namespace Modules\Library\app\Listeners\BookListeners\BookCreatedListener;

use Modules\Library\app\Events\BookEvents\BookCreated;

class BookCreatedLogEventListener
{
    public function handle(BookCreated $event)
    {
        $book = $event->book;

        activity()
            ->causedBy(auth()->user())
            ->performedOn($book)
            ->withProperties([
                'book_id' => $book->id,
            ])
            ->log('book.created');
    }
}
