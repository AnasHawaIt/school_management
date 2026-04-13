<?php


namespace Modules\Transport\Listeners\BusListeners\BusUpdateListeners;


use Modules\Transport\Events\Broadcasts\BusBroadcast;
use Modules\Transport\Events\BusEvents\CategoryUpdated;

class BusUpdateBroadcastEventListener
{

    public function handle(CategoryUpdated $event)
    {
        broadcast(new BusBroadcast($event->bus))->toOthers();
    }
}
