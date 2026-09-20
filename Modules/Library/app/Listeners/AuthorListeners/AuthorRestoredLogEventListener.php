<?php

namespace Modules\Library\app\Listeners\AuthorListeners;

use Modules\Library\app\Events\AuthorEvents\AuthorRestored;

class AuthorRestoredLogEventListener
{
    public function handle(AuthorRestored $event)
    {
        $author = $event->author;
        activity()
            ->causedBy(auth()->user())
            ->performedOn($author)
            ->withProperties([
                'author_id' => $author->id,
            ])
            ->log('author.Restored');
    }
}
