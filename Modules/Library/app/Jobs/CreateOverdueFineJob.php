<?php

namespace Modules\Library\app\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Modules\Library\Entities\Borrowing;
use Modules\Library\Entities\Fine;
use Modules\Library\Services\FineService;

class CreateOverdueFineJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public int $borrowingId
    ) {
    }

    public function handle(
        FineService $fineService
    ): void {
        $borrowing = Borrowing::query()
            ->find($this->borrowingId);

        /*
         * Borrowing may have been deleted/removed
         * before the queued job executes.
         */
        if (!$borrowing) {
            return;
        }

        /*
         * Fine should only be created for late borrowings.
         */
        if ($borrowing->status !== 'late') {
            return;
        }

        /*
         * Idempotency:
         * Do not create another fine for the same transaction.
         */
        $existingFine = Fine::query()
            ->where('transaction_id', $borrowing->id)
            ->exists();

        if ($existingFine) {
            return;
        }

        /*
         * Default fine configuration.
         */
        $dailyFine = (float) config(
            'library.overdue_fine_per_day',
            1
        );

        /*
         * Calculate overdue days.
         */
        $overdueDays = 1;

        if ($borrowing->due_date) {
            $overdueDays = max(
                1,
                $borrowing->due_date
                    ->startOfDay()
                    ->diffInDays(now()->startOfDay())
            );
        }

        $amount = round(
            $overdueDays * $dailyFine,
            2
        );

        $fineService->create([
            'transaction_id' => $borrowing->id,
            'amount' => $amount,
            'status' => 'unpaid',
            'notes' => sprintf(
                'Overdue fine for %d day(s).',
                $overdueDays
            ),
        ]);
    }
}
