<?php

namespace Modules\Transport\app\Listeners\BusListeners\BusDeletedListeners;

use Modules\Transport\app\Events\BusEvents\BusDeleted;

class BusDeletedLogEventListener
{
    public function handle(BusDeleted $event)
    {
        $bus = $event->bus;

        activity()
            ->causedBy($event->userId)
            ->performedOn($bus)
            ->withProperties([
                'Bus_id' => $bus->id,
            ])
            ->log('Bus.Deleted');
    }
}
