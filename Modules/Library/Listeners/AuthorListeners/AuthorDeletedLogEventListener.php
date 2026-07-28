<?php

namespace Modules\Library\Listeners\AuthorListeners;

use Modules\Library\Events\AuthorEvents\AuthorDeleted;

class AuthorDeletedLogEventListener
{
    public function handle(AuthorDeleted $event)
    {
        $author = $event->author;
        activity()
            ->causedBy($event->userId)
            ->performedOn($author)
            ->withProperties([
                'author_id' => $author->id,
            ])
            ->log('author.deleted');
    }
}
