<?php

namespace Modules\Library\Listeners\FineListeners;

use Illuminate\Support\Facades\Log;
use Modules\Library\Events\FinesEvents\FinePaid;

class LogFinePaid
{
    public function handle(FinePaid $event): void
    {
        $fine = $event->fine;

        Log::info('Library fine paid.', [
            'fine_id' => $fine->id,
            'transaction_id' => $fine->transaction_id,
            'amount' => $fine->amount,
            'paid_by' => $fine->paid_by,
            'paid_at' => $fine->paid_at?->toDateTimeString(),
        ]);
    }
}
