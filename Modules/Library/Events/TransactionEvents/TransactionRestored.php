<?php


namespace Modules\Library\Events\TransactionEvents;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Modules\Library\Entities\Transaction;

class TransactionRestored
{
    use Dispatchable, SerializesModels;

    public Transaction $transaction;

    public function __construct(Transaction $transaction,public ?int $userId = null)
    {
        $this->transaction =$transaction;
    }
}
