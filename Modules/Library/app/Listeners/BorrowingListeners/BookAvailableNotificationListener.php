<?php

namespace Modules\Library\app\Listeners\BorrowingListeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\DB;
use Modules\Library\app\Entities\Reservation;
use Modules\Library\app\Events\BorrowingEvents\BookAvailable;
use Modules\Notifications\app\Services\NotificationService;

class BookAvailableNotificationListener implements ShouldQueue
{
    public function __construct(
        protected NotificationService $notificationService
    ) {
    }

    public function handle(BookAvailable $event): void
    {
        $reservation = DB::transaction(function () use ($event) {
            $reservation = Reservation::query()
                ->where('book_id', $event->book->id)
                ->where('status', 'pending')
                ->oldest()
                ->lockForUpdate()
                ->first();

            if ($reservation) {
                $reservation->update([
                    'status' => 'notified',
                    'notified_at' => now(),
                ]);
            }

            return $reservation?->load('member.user');
        });

        $user = $reservation?->member?->user;
        if (! $user) {
            return;
        }

        $this->notificationService->send(
            user: $user,
            title: 'الكتاب متاح الآن',
            body: 'الكتاب المحجوز متاح الآن للاستعارة.',
            type: 'Library',
            data: [
                'entity' => 'reservation',
                'action' => 'available',
                'reservation_id' => $reservation->id,
                'book_id' => $event->book->id,
            ]
        );
    }
}
