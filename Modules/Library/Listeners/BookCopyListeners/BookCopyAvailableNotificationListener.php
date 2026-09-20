<?php

namespace Modules\Library\Listeners\BookCopyListeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\DB;
use Modules\Library\Entities\Reservation;
use Modules\Library\Events\BookCopiesEvents\BookCopyAvailable;
use Modules\Notifications\Services\NotificationService;

class BookCopyAvailableNotificationListener implements ShouldQueue
{
    public function __construct(
        protected NotificationService $notificationService
    ) {
    }

    public function handle(BookCopyAvailable $event): void
    {
        $reservation = DB::transaction(function () use ($event) {

            $reservation = Reservation::query()
                ->where('book_id', $event->copy->book_id)
                ->where('status', 'pending')
                ->oldest('id')
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
            body: 'النسخة المحجوزة من الكتاب متاحة الآن للاستعارة.',
            type: 'Library',
            data: [
                'entity' => 'reservation',
                'action' => 'available',
                'reservation_id' => $reservation->id,
                'book_id' => $event->copy->book_id,
                'copy_id' => $event->copy->id,
            ]
        );
    }
}
