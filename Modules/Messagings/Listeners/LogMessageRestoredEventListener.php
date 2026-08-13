<?php

namespace Modules\Messagings\Listeners;


use Modules\Messagings\Events\MessageRestored;

class LogMessageRestoredEventListener
{
    public function handle(MessageRestored $event): void
    {
        activity()
            ->performedOn($event->message)
            ->log('Message.Restored');
    }
}
