<?php


namespace Modules\Transport\app\Listeners\BusListeners\BusUpdateListeners;


use Modules\Transport\app\Events\Broadcasts\BusBroadcast;
use Modules\Transport\app\Events\BusEvents\BusUpdated;

class BusUpdateBroadcastEventListener
{

    public function handle(BusUpdated $event)
    {
        broadcast(new BusBroadcast($event->bus))->toOthers();
    }
}
