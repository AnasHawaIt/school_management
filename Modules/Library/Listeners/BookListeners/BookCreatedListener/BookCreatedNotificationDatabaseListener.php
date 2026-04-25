<?php

namespace Modules\Library\Listeners\BookListeners\BookCreatedListener;

use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Core\Entities\User;
use Modules\Library\Events\BookEvents\BookCreated;
use Modules\Library\Notifications\BookCreatedNotification;

class BookCreatedNotificationDatabaseListener  implements ShouldQueue
{
    public function handle(BookCreated $event)
    {
        $users = User::all();

        foreach ($users as $user) {
            $user->notify(new BookCreatedNotification($event->book));
        }
    }
}
