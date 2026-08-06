<?php

namespace Modules\Notifications\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Modules\Notifications\Entities\Notification;

class NotificationCreated
{
    use Dispatchable, SerializesModels;

    public Notification $notification;

    public function __construct(Notification $notification)
    {
        $this->notification = $notification;
    }
}
