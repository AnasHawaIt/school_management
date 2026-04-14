<?php


namespace Modules\Library\Events\AuthorEvents;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Modules\Library\Entities\Author;

class AuthorRestored
{
    use Dispatchable, SerializesModels;

    public Author $author;

    public function __construct(Author $author)
    {
        $this->author = $author;
    }
}
