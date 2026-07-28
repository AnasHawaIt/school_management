<?php

namespace Modules\Transport\Listeners\BusListeners\BusUpdateListeners;

use Modules\Transport\Entities\EventLog;
use Modules\Transport\Events\BusEvents\BusUpdated;

class BusUpdateLogEventListener
{
    public function handle(BusUpdated $event)
    {
        $bus = $event->bus;

        activity()
            ->causedBy($event->userId)
            ->performedOn($bus)
            ->withProperties([
                'Bus_id' => $bus->id,
            ])
            ->log('Bus.updated');
    }
}
