<?php


namespace Modules\Library\Listeners\TransactionListeners;


use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Library\Events\Broadcasts\TransactionBroadcast;
use Modules\Library\Events\TransactionEvents\TransactionUpdated;

class TransactionUpdatedBroadcastEventListener  implements ShouldQueue
{

    public function handle(TransactionUpdated $event)
    {
        broadcast(new TransactionBroadcast($event->transaction))->toOthers();
    }
}
