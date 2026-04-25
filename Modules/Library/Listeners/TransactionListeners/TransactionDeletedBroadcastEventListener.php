<?php


namespace Modules\Library\Listeners\TransactionListeners;



use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Library\Events\Broadcasts\TransactionBroadcast;
use Modules\Library\Events\TransactionEvents\TransactionDeleted;

class TransactionDeletedBroadcastEventListener  implements ShouldQueue
{

    public function handle(TransactionDeleted $event)
    {
        broadcast(new TransactionBroadcast($event->transaction))->toOthers();
    }
}
