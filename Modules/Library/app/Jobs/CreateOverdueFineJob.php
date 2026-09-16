<?php

namespace Modules\Library\app\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Modules\Library\Entities\Borrowing;
use Modules\Library\Entities\Fine;
use Modules\Library\Services\FineService;
use Modules\Library\app\Enums\BorrowingStatus;
use Modules\Library\app\Enums\FineStatus;

class CreateOverdueFineJob implements ShouldQueue, ShouldBeUnique
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;
    public int $timeout = 60;
    public int $uniqueFor = 3600;

    public function __construct(
        public int $borrowingId
    ) {
    }

    public function uniqueId(): string
    {
        return (string) $this->borrowingId;
    }

    public function backoff(): array
    {
        return [30, 120];
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
        if ($borrowing->status !== BorrowingStatus::LATE) {
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
            'status' => FineStatus::UNPAID,
            'notes' => sprintf(
                'Overdue fine for %d day(s).',
                $overdueDays
            ),
        ]);
    }
}
