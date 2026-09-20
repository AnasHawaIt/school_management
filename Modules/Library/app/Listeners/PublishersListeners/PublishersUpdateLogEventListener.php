<?php

namespace Modules\Library\app\Listeners\PublishersListeners;

use Modules\Library\app\Events\PublishersEvents\PublishersUpdated;

class PublishersUpdateLogEventListener
{
    public function handle(PublishersUpdated $event)
    {
        $publisher = $event->publisher;

        activity()->causedBy(auth()->user())
            ->performedOn($publisher)
            ->withProperties([
                'publisher_id' => $publisher->id,
            ])
            ->log('publisher.updated');
    }
}
