<?php

namespace Modules\Transport\app\Listeners\BusListeners\BusUpdateListeners;

use Modules\Transport\app\Events\BusEvents\BusUpdated;

class BusUpdateLogEventListener
{
    public function handle(BusUpdated $event)
    {
        $bus = $event->bus;

        activity()
            ->causedBy(auth()->user())
            ->performedOn($bus)
            ->withProperties([
                'Bus_id' => $bus->id,
            ])
            ->log('Bus.updated');
    }
}
