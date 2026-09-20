<?php

namespace Modules\Library\app\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Modules\Library\app\Entities\Reservation;
use Modules\Library\app\Enums\ReservationStatus;
use Modules\Library\app\Services\ReservationService;

class ExpireReservationsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct()
    {
    }

    public function handle(ReservationService $service): void
    {
        $expiryDays = (int) config(
            'library.reservation_expiry_days',
            2
        );

        $pendingDeadline = now()->subDays($expiryDays);

        Reservation::query()
            ->whereIn('status', [
                ReservationStatus::PENDING,
                ReservationStatus::NOTIFIED,
            ])
            ->where(function ($query) use ($pendingDeadline) {

                /*
                 * Pending reservations expire based on created_at.
                 */
                $query
                    ->where(function ($query) use ($pendingDeadline) {
                        $query
                            ->where(
                                'status',
                                ReservationStatus::PENDING
                            )
                            ->where(
                                'created_at',
                                '<=',
                                $pendingDeadline
                            );
                    })

                    /*
                     * Notified reservations expire based on notified_at.
                     */
                    ->orWhere(function ($query) use ($pendingDeadline) {
                        $query
                            ->where(
                                'status',
                                ReservationStatus::NOTIFIED
                            )
                            ->whereNotNull('notified_at')
                            ->where(
                                'notified_at',
                                '<=',
                                $pendingDeadline
                            );
                    });
            })
            ->orderBy('id')
            ->chunkById(100, function ($reservations) use ($service) {

                foreach ($reservations as $reservation) {
                    try {
                        $service->expireReservation(
                            $reservation->id
                        );
                    } catch (\Throwable $e) {
                        /*
                         * Do not stop the entire job if one
                         * reservation cannot be expired.
                         */
                        report($e);
                    }
                }
            });
    }
}
