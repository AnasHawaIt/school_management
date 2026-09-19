<?php

namespace Modules\Library\app\Listeners\AuthorListeners;

use Modules\Library\app\Events\AuthorEvents\AuthorDeleted;

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
