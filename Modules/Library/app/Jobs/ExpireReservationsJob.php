<?php

namespace Modules\Library\app\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Library\Entities\Reservation;
use Modules\Library\Services\ReservationService;

class ExpireReservationsJob implements ShouldQueue
{
    public function handle(): void
    {
        Reservation::query()
            ->whereIn('status', ['pending', 'notified'])
            ->whereNotNull('expires_at')
            ->where('expires_at', '<=', now())
            ->chunkById(100, function ($reservations) {

                foreach ($reservations as $reservation) {
                    app(ReservationService::class)
                        ->expire($reservation->id);
                }

            });
    }
}
