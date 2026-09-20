<?php

namespace Modules\Library\app\Listeners\PublishersListeners;

use Modules\Library\app\Events\PublishersEvents\PublishersRestored;

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
