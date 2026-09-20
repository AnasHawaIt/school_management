<?php

namespace Modules\Library\app\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Modules\Library\app\Services\ReservationService;

class ProcessReservationQueueJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public function __construct(
        public int $bookId
    ) {
    }

    public function handle(
        ReservationService $service
    ): void {
        $service->notifyNextMember(
            $this->bookId
        );
    }
}
