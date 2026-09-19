<?php

namespace Modules\Library\app\Listeners\BookListeners\BookUpdatedListener;

use Modules\Library\app\Events\BookEvents\BookUpdated;

class BookUpdatedLogEventListener
{
    public function handle(BookUpdated $event)
    {
        $book = $event->book;

        activity()
            ->causedBy($event->userId)
            ->performedOn($book)
            ->withProperties([
                'book_id' => $book->id,
            ])
            ->log('book.updated');
    }
}
