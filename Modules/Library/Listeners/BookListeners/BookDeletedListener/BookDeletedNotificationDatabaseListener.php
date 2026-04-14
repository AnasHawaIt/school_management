<?php

namespace Modules\Library\Listeners\BookListeners\BookDeletedListener;


use Modules\Core\Entities\User;
use Modules\Library\Events\BookEvents\BookDeleted;
use Modules\Library\Notifications\BookDeletedNotification;

class BookDeletedNotificationDatabaseListener
{
    public function handle(BookDeleted $event)
    {
        $users = User::all();

        foreach ($users as $user) {
            $user->notify(new BookDeletedNotification($event->book));
        }
    }
}
