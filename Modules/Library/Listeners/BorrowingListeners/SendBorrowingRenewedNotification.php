<?php


namespace Modules\Library\Listeners\BorrowingListeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Library\Events\BorrowingEvents\BorrowingRenewed;
use Modules\Notifications\Services\NotificationService;

class SendBorrowingRenewedNotification implements ShouldQueue
{
    public function __construct(
        protected NotificationService $notificationService
    )
    {
    }

    public function handle(BorrowingRenewed $event): void
    {
        $borrowing = $event->borrowing;

        $user = $borrowing->member?->user;

        if (!$user) {
            return;
        }

        $this->notificationService->send(
            user: $user,
            title: 'طلب تجديد الاستعارة  ',
            body: 'لديك طلب تحديد الاستعارة ',
            type: 'Library',
            data: [
                'entity' => 'borrowing',
                'action' => 'Rejected',
                'borrowing_id' => $borrowing->id,
                'book_id' => $borrowing->book_id,
            ]
        );
    }
}
