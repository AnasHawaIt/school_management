<?php


namespace Modules\Library\Events\BookEvents;

use Modules\Library\Entities\Book;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class BookRestored
{
    use Dispatchable, SerializesModels;

    public Book $book;

    public function __construct(Book $book,public ?int $userId = null )
    {
        $this->book = $book;
    }
}
