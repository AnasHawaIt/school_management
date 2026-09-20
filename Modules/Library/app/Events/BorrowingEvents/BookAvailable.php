<?php


namespace Modules\Library\app\Events\BookEvents;

namespace Modules\Library\app\Events\BorrowingEvents;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Modules\Library\app\Entities\Book;

class BookAvailable
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public Book $book,
    )
    {
    }
}
