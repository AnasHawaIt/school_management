<?php

namespace Modules\Library\Listeners\BookListeners\BookCreatedListener;

use Modules\Core\Entities\User;
use Modules\Library\Events\BookEvents\BookCreated;
use Modules\Library\Notifications\BookCreatedNotification;

class BookCreatedNotificationDatabaseListener
{
    public function handle(BookCreated $event)
    {
        $users = User::all();

        foreach ($users as $user) {
            $user->notify(new BookCreatedNotification($event->book));
        }
    }
}
