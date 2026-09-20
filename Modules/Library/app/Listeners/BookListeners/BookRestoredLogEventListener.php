<?php

namespace Modules\Library\app\Listeners\BookListeners;

use Modules\Library\app\Events\BookEvents\BookRestored;

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
