<?php


namespace Modules\Library\Events\BookEvents;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Modules\Library\Entities\Book;

class BookAvailable
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public Book $book,
    )
    {
    }
}
