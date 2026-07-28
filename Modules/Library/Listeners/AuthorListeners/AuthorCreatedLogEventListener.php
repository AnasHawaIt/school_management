<?php

namespace Modules\Library\Listeners\AuthorListeners;

use Modules\Library\Events\AuthorEvents\AuthorCreated;

class AuthorCreatedLogEventListener
{
    public function handle(AuthorCreated $event)
    {
        $author = $event->author;
        activity()
            ->causedBy($event->userId)
            ->performedOn($author)
            ->withProperties([
                'author_id' => $author->id,
            ])
            ->log('author.created');
    }
}
