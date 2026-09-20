<?php

namespace Modules\Library\app\Listeners\BorrowingListeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Library\app\Events\BorrowingEvents\BorrowingCreated;
use Modules\Notifications\app\Services\NotificationService;

class SendBorrowingCreatedNotification implements ShouldQueue
{
    public function __construct(
        protected NotificationService $notificationService
    )
    {
    }

    public function handle(BorrowingCreated $event): void
    {


        $borrowing = $event->borrowing;

        $user = $borrowing->member?->user;

        if (!$user) {
            return;
        }

        $this->notificationService->send(
            user: $user,
            title: 'طلب استعارة ',
            body: 'لديك كتاب جديد في الحجز    .',
            type: 'Library',
            data: [
                'entity' => 'borrowing',
                'action' => 'Create',
                'book_id' => $borrowing->book_id,
            ]
        );

    }
}
