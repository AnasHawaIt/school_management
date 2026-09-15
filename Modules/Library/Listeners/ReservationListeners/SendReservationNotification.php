<?php

namespace Modules\Library\Listeners\ReservationListeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Library\Events\ReservationEvents\ReservationNotified;
use Modules\Notifications\Services\NotificationService;

class SendReservationNotification implements ShouldQueue
{
    public function __construct(
        protected NotificationService $notificationService
    ) {
    }

    public function handle(ReservationNotified $event): void
    {
        $reservation = $event->reservation;

        $member = $reservation->member;
        $book = $reservation->book;

        if (!$member || !$member->user) {
            return;
        }

        $this->notificationService->send(
            user: $member->user,
            title: 'Book Available',
            body: "The book \"{$book->title}\" is now available for you.",
            type: 'library.reservation.available',
            data: [
                'reservation_id' => $reservation->id,
                'book_id' => $book->id,
                'type' => 'reservation_available',
            ]
        );

        $reservation->update([
            'notified_at' => now(),
        ]);
    }
}
