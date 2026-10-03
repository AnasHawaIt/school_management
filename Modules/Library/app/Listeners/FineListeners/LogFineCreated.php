<?php

namespace Modules\Library\Listeners\FineListeners;

use Illuminate\Support\Facades\Log;
use Modules\Library\Events\FinesEvents\FineCreated;

class LogFineCreated
{
    public function handle(FineCreated $event): void
    {
        $fine = $event->fine;

        Log::info('Library fine created.', [
            'fine_id' => $fine->id,
            'transaction_id' => $fine->transaction_id,
            'amount' => $fine->amount,
            'status' => $fine->status,
        ]);
    }
}
