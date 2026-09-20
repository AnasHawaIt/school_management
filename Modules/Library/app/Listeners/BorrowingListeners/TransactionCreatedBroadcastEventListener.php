<?php


namespace Modules\Library\app\Listeners\BorrowingListeners;


use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Library\app\Events\Broadcasts\TransactionBroadcast;
use Modules\Library\app\Events\BorrowingEvents\BorrowingRejected;

class TransactionCreatedBroadcastEventListener  implements ShouldQueue
{

    public function handle(BorrowingRejected $event)
    {
        broadcast(new TransactionBroadcast($event->borrowing))->toOthers();
    }
}
