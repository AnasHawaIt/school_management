<?php

namespace Modules\Library\app\Listeners\FineListeners;

use Illuminate\Support\Facades\Log;
use Modules\Library\app\Events\FinesEvents\FineWaived;

class LogFineWaived
{
    public function handle(FineWaived $event): void
    {
        $fine = $event->fine;

        Log::warning('Library fine waived.', [
            'fine_id' => $fine->id,
            'transaction_id' => $fine->transaction_id,
            'amount' => $fine->amount,
            'waived_by' => $fine->waived_by,
            'waived_at' => $fine->waived_at?->toDateTimeString(),
        ]);
    }
}
