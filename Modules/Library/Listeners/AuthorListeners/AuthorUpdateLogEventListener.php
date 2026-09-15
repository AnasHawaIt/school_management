<?php

namespace Modules\Library\Listeners\AuthorListeners;

use Modules\Library\Events\AuthorEvents\AuthorUpdated;

class AuthorUpdateLogEventListener
{
    public function handle(AuthorUpdated $event)
    {
        $author = $event->author;
        activity()
            ->causedBy(auth()->user())
            ->performedOn($author)
            ->withProperties([
                'author_id' => $author->id,
            ])
            ->log('author.updated');
    }
}
