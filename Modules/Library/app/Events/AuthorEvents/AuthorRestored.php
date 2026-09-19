<?php


namespace Modules\Library\app\Events\AuthorEvents;

use App\Models\User;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Modules\Library\app\Entities\Author;

class AuthorRestored
{
    use Dispatchable, SerializesModels;

    public Author $author;


    public function __construct(Author $author, public ?int $userId = null)
    {
        $this->author = $author;

    }
}
