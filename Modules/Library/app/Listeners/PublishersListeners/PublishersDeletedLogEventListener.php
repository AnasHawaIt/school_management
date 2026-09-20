<?php

namespace Modules\Library\app\Listeners\PublishersListeners;

use Modules\Library\app\Events\PublishersEvents\PublishersDeleted;

class PublishersDeletedLogEventListener
{
    public function handle(PublishersDeleted $event)
    {
        $publisher = $event->publisher;

        activity()
            ->causedBy(auth()->user())
            ->performedOn($publisher)
            ->withProperties([
                'publisher_id' => $publisher->id,
            ])
            ->log('publisher.deleted');
    }
}
