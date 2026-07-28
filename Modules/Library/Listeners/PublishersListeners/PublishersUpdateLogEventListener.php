<?php

namespace Modules\Library\Listeners\PublishersListeners;

use Modules\Library\Events\PublishersEvents\PublishersUpdated;

class PublishersUpdateLogEventListener
{
    public function handle(PublishersUpdated $event)
    {
        $publisher = $event->publisher;

        activity()
            ->causedBy($event->userId)
            ->performedOn($publisher)
            ->withProperties([
                'publisher_id' => $publisher->id,
            ])
            ->log('publisher.updated');
    }
}
