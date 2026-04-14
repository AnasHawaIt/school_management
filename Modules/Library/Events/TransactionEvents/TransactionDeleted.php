<?php

namespace Modules\Library\Events\TransactionEvents;

use Modules\Library\Entities\Transaction;

class TransactionDeleted
{
    public function __construct(
        public Transaction $transaction,
        public ?int $userId = null
    ) {}
}


