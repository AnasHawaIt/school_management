<?php


namespace Modules\Library\Console\Commands;

use Illuminate\Console\Command;
use Modules\Library\Entities\Borrowing;
use Modules\Library\Events\BorrowingEvents\BorrowingOverdue;

class CheckOverdueBorrowings extends Command
{
    protected $signature = 'library:check-overdue';

    protected $description = 'Check overdue library borrowings';

    public function handle(): int
    {
        Borrowing::query()
            ->whereNull('returned_at')
            ->where('due_date', '<', now())
            ->where('status', 'borrowed')
            ->chunkById(100, function ($borrowings) {

                foreach ($borrowings as $borrowing) {

                    event(
                        new BorrowingOverdue($borrowing)
                    );
                }

            });

        return self::SUCCESS;
    }
}
