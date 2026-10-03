<?php


namespace Modules\Transport\Listeners\BusListeners\BusUpdateListeners;


use Modules\Transport\Events\Broadcasts\BusBroadcast;
use Modules\Transport\Events\BusEvents\BusUpdated;

class BusUpdateBroadcastEventListener
{

    public function handle(BusUpdated $event)
    {
        broadcast(new BusBroadcast($event->bus))->toOthers();
    }
}
