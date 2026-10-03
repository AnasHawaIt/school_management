<?php

namespace Modules\Library\Listeners\BorrowingListeners;

use Modules\Library\Jobs\CreateOverdueFineJob;
use Modules\Library\Events\BorrowingEvents\BorrowingOverdue;

class CreateOverdueFineListener
{
    public function handle(BorrowingOverdue $event): void
    {
        CreateOverdueFineJob::dispatch(
            $event->borrowing->id
        );
    }
}
