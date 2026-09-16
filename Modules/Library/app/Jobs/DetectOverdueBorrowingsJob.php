<?php

namespace Modules\Library\app\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Modules\Library\Entities\Borrowing;
use Modules\Library\Services\TransactionService;
use Modules\Library\app\Enums\BorrowingStatus;

class DetectOverdueBorrowingsJob implements ShouldQueue, ShouldBeUnique
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;
    public int $timeout = 120;
    public int $uniqueFor = 600;

    public function backoff(): array
    {
        return [30, 120];
    }

    public function __construct()
    {
    }

    public function handle(TransactionService $service): void
    {
        Borrowing::query()
            ->where('status', BorrowingStatus::BORROWED)
            ->whereNotNull('due_date')
            ->where('due_date', '<=', now())
            ->orderBy('id')
            ->chunkById(100, function ($borrowings) use ($service) {

                foreach ($borrowings as $borrowing) {
                    try {
                        /*
                         * markOverdue() itself validates:
                         * - status must be borrowed
                         * - due_date must actually be overdue
                         */
                        $service->markOverdue($borrowing->id);
                    } catch (\Throwable $e) {
                        /*
                         * One failed borrowing must not stop
                         * the entire overdue detection process.
                         */
                        report($e);
                    }
                }
            });
    }
}
