<?php

namespace Modules\Library\Listeners\BookListeners;

use Modules\Library\Entities\Reservation;
use Modules\Library\Events\BookEvents\BookAvailable;
use Modules\Notifications\Services\NotificationService;

class BookAvailableNotificationListener
{
    public function __construct(
        protected NotificationService $notificationService
    ) {
    }

    public function handle(BookAvailable $event): void
    {
        $reservation = Reservation::query()
            ->with('member.user')
            ->where('book_id', $event->book->id)
            ->where('status', 'pending')
            ->oldest()
            ->first();

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
