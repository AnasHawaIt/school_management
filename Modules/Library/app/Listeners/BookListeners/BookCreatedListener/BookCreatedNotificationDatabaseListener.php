<?php

namespace Modules\Library\Listeners\BookListeners\BookCreatedListener;

use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Library\Events\BookEvents\BookCreated;
use Modules\Notifications\Services\NotificationService;

class BookCreatedNotificationDatabaseListener implements ShouldQueue
{
    public function __construct(
        protected NotificationService $notificationService
    ) {
    }

    public function handle(BookCreated $event): void
    {
        $book = $event->book;

        $this->notificationService->sendToAll(
            title: 'كتاب جديد',
            body: "تمت إضافة كتاب جديد: {$book->title}",
            type: 'Library',
            data: [
                'entity' => 'Book',
                'action' => 'Created',
                'book_id' => $book->id,
            ]
        );
    }
}
