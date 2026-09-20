<?php


namespace Modules\Library\app\Console\Commands;

use Illuminate\Console\Command;
use Modules\Library\app\Entities\Borrowing;
use Modules\Library\app\Entities\Fine;
use Modules\Library\app\Events\BorrowingEvents\BorrowingOverdue;

class CheckOverdueBorrowings extends Command
{
    protected $signature = 'library:check-overdue';

    protected $description = 'Check overdue library borrowings';

    public function handle(): int
    {
        Borrowing::query()
            ->whereNull('returned_at')
            ->where('due_date', '<', today())
            ->whereIn('status', ['borrowed', 'late'])
            ->chunkById(100, function ($borrowings) {

                foreach ($borrowings as $borrowing) {
                    if ($borrowing->status !== 'late') {
                        $borrowing->update(['status' => 'late']);
                    }

                    $daysLate = max(1, $borrowing->due_date->diffInDays(today()));
                    $fine = Fine::firstOrNew(['transaction_id' => $borrowing->id]);
                    if (!$fine->exists || $fine->status === 'unpaid') {
                        $fine->amount = $daysLate * config('library.fine_per_day');
                        $fine->status ??= 'unpaid';
                        $fine->save();
                    }

                    event(
                        new BorrowingOverdue($borrowing)
                    );
                }

            });

        return self::SUCCESS;
    }
}
