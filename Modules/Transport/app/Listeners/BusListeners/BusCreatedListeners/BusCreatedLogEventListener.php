<?php

namespace Modules\Transport\app\Listeners\BusListeners\BusCreatedListeners;

use Modules\Transport\app\Events\BusEvents\BusCreated;

class BusCreatedLogEventListener
{
    public function handle(BusCreated $event)
    {
        $bus = $event->bus;

        activity()
            ->causedBy($event->userId)
            ->performedOn($bus)
            ->withProperties([
                'Bus_id' => $bus->id,
            ])
            ->log('Bus.created');
    }
}
