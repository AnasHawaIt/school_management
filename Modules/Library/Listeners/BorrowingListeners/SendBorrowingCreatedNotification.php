<?php

namespace Modules\Library\Listeners\BorrowingListeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Library\Events\BorrowingEvents\BorrowingCreated;
use Modules\Notifications\Services\NotificationService;

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
            title: 'كتاب جديد ',
            body: 'لديك كتاب جديد في المكتبة   .',
            type: 'Library',
            data: [
                'entity' => 'borrowing',
                'action' => 'Create',
                'book_id' => $borrowing->book_id,
            ]
        );

    }
}
