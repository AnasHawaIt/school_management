<?php

namespace Modules\Transport\Listeners\BusListeners\BusCreatedListeners;

use Modules\Transport\Entities\EventLog;
use Modules\Transport\Events\BusEvents\BusCreated;

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
