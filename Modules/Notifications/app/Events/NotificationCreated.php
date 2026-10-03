<?php

namespace Modules\Notifications\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Modules\Notifications\Entities\Notification;

class NotificationCreated
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public Notification $notification
    ) {}
}
