<?php

namespace Modules\Library\app\Listeners\PublishersListeners;

use Modules\Library\app\Events\PublishersEvents\PublishersCreated;

class PublishersCreatedLogEventListener
{
    public function handle(PublishersCreated $event)
    {
        $publisher = $event->publisher;

        activity()->causedBy(auth()->user())
            ->performedOn($publisher)
            ->withProperties([
                'publisher_id' => $publisher->id,
            ])
            ->log('publisher.created');
    }
}
