<?php


namespace Modules\Library\app\Console\Commands;

use Illuminate\Console\Command;
use Modules\Library\app\Entities\Borrowing;
use Modules\Library\app\Events\BorrowingEvents\BorrowingOverdue;

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
