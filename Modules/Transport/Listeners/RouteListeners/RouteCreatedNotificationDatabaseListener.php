<?php

namespace Modules\Transport\Listeners\RouteListeners;


use Illuminate\Support\Facades\Notification;
use Modules\Core\Entities\User;
use Modules\Transport\Events\RouteEvents\RouteCreated;
use Modules\Transport\Notifications\RouteCreatedNotification;

class RouteCreatedNotificationDatabaseListener
{
    public function handle(RouteCreated $event)
    {
        $users = User::all();

        foreach ($users as $user) {
            $user->notify(new RouteCreatedNotification($event ->route));
        }
    }
}
