<?php


namespace Modules\Transport\Listeners\BusListeners\BusDeletedListeners;


use Modules\Transport\Events\Broadcasts\BusBroadcast;
use Modules\Transport\Events\BusEvents\CategoryDeleted;

class BusDeletedBroadcastEventListener
{

    public function handle(CategoryDeleted $event)
    {
        broadcast(new BusBroadcast($event->bus))->toOthers();
    }
}
