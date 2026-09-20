<?php

namespace Modules\Library\app\Listeners\BorrowingListeners;

use Modules\Library\app\Jobs\CreateOverdueFineJob;
use Modules\Library\app\Events\BorrowingEvents\BorrowingOverdue;

class CreateOverdueFineListener
{
    public function handle(BorrowingOverdue $event): void
    {
        CreateOverdueFineJob::dispatch(
            $event->borrowing->id
        );
    }
}
