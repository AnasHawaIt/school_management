<?php

namespace Modules\Library\Listeners\PublishersListeners;

use Modules\Library\Events\PublishersEvents\PublishersCreated;

class PublishersCreatedLogEventListener
{
    public function handle(PublishersCreated $event)
    {
        $publisher = $event->publisher;

        activity()
            ->causedBy($event->userId)
            ->performedOn($publisher)
            ->withProperties([
                'publisher_id' => $publisher->id,
            ])
            ->log('publisher.created');
    }
}
