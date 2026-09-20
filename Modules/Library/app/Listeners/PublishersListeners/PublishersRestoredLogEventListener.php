<?php

namespace Modules\Library\Listeners\PublishersListeners;

use Modules\Library\Events\PublishersEvents\PublishersRestored;

class PublishersRestoredLogEventListener
{
    public function handle(PublishersRestored $event)
    {
        $publisher = $event->publisher;

        activity()
            ->causedBy(auth()->user())
            ->performedOn($publisher)
            ->withProperties([
                'publisher_id' => $publisher->id,
            ])
            ->log('publisher.restored');
    }
}
