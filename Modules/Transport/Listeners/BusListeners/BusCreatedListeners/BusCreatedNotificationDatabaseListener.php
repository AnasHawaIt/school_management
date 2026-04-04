<?php

namespace Modules\Transport\Listeners\BusListeners\BusCreatedListeners;

use Modules\Core\Entities\User;
use Modules\Transport\Events\BusEvents\BusCreated;
use Modules\Transport\Notifications\BusCreatedNotification;

class BusCreatedNotificationDatabaseListener
{
        public function handle(BusCreated $event)
    {
        $users = User::all();

        foreach ($users as $user) {
            $user->notify(new BusCreatedNotification($event->bus));
        }
    }

}
