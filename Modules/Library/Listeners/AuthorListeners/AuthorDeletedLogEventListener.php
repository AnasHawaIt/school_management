<?php

namespace Modules\Library\Listeners\AuthorListeners;

use Modules\Library\Events\AuthorEvents\AuthorDeleted;

class AuthorDeletedLogEventListener
{
    public function handle(AuthorDeleted $event)
    {
        $author = $event->author;
        activity()
            ->causedBy(auth()->user())
            ->performedOn($author)
            ->withProperties([
                'author_id' => $author->id,
            ])
            ->log('author.deleted');
    }
}
