<?php

namespace Modules\Library\Events\AuthorEvents;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Modules\Library\Entities\Author;

class AuthorUpdated
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public Author $author,
        public ?int $userId = null,
        public array $changes = []
    ) {}
}

