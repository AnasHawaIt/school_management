<?php

namespace Modules\Library\Listeners\AuthorListeners;

use Modules\Library\Events\AuthorEvents\AuthorUpdated;

class AuthorUpdateLogEventListener
{
    public function handle(AuthorUpdated $event)
    {
        $author = $event->author;
        activity()
            ->causedBy($event->userId)
            ->performedOn($author)
            ->withProperties([
                'author_id' => $author->id,
            ])
            ->log('author.updated');
    }
}
