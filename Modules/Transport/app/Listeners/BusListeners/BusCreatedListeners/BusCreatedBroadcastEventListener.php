<?php


namespace Modules\Transport\app\Listeners\BusListeners\BusCreatedListeners;


use Modules\Transport\app\Events\Broadcasts\BusBroadcast;
use Modules\Transport\app\Events\BusEvents\BusCreated;

class BusCreatedBroadcastEventListener
{

    public function handle(BusCreated $event)
    {
        broadcast(new BusBroadcast($event->bus))->toOthers();
    }
}
