<?php

namespace Modules\Library\Listeners\AuthorListeners;

use Modules\Library\Events\AuthorEvents\AuthorForceDeleted;

class AuthorForceDeletedLogEventListener
{
    public function handle(AuthorForceDeleted $event)
    {
        $author = $event->author;
        activity()
            ->causedBy(auth()->user())
            ->performedOn($author)
            ->withProperties([
                'author_id' => $author->id,
            ])
            ->log('author.forceDeleted');
    }
}
