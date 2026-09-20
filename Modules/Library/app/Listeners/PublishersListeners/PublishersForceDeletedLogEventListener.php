<?php

namespace Modules\Library\app\Listeners\PublishersListeners;

use Modules\Library\app\Events\PublishersEvents\PublishersForceDeleted;

class PublishersForceDeletedLogEventListener
{
    public function handle(PublishersForceDeleted $event)
    {
        $publisher = $event->publisher;

        activity()
            ->causedBy(auth()->user())
            ->performedOn($publisher)
            ->withProperties([
                'publisher_id' => $publisher->id,
            ])
            ->log('publisher.forceDeleted');
    }
}
