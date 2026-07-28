<?php


namespace Modules\Transport\Listeners\BusListeners\BusCreatedListeners;


use Modules\Transport\Events\Broadcasts\BusBroadcast;
use Modules\Transport\Events\BusEvents\BusCreated;

class BusCreatedBroadcastEventListener
{

    public function handle(BusCreated $event)
    {
        broadcast(new BusBroadcast($event->bus))->toOthers();
    }
}
