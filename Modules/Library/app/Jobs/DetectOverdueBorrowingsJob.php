<?php

namespace Modules\Library\app\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Modules\Library\app\Entities\Borrowing;
use Modules\Library\app\Services\TransactionService;

class DetectOverdueBorrowingsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct()
    {
    }

    public function handle(TransactionService $service): void
    {
        Borrowing::query()
            ->where('status', 'borrowed')
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
