<?php

namespace App\Listeners;

use App\Events\ModelEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Notification;
use App\Notifications\GenericModelNotification;

class NotifyModelEvent implements ShouldQueue
{
    use InteractsWithQueue;

    public function handle(ModelEvent $event): void
    {
        // مثال: إشعار لكل المستخدمين أو مجموعة محددة
        $users = \Modules\Core\Entities\User::all(); // أو حسب النظام
        Notification::send($users, new GenericModelNotification($event));
    }
}
