<?php


namespace Modules\Library\Listeners\TransactionListeners;



use Modules\Library\Events\Broadcasts\TransactionBroadcast;
use Modules\Library\Events\TransactionEvents\TransactionDeleted;

class TransactionDeletedBroadcastEventListener
{

    public function handle(TransactionDeleted $event)
    {
        broadcast(new TransactionBroadcast($event->transaction))->toOthers();
    }
}
