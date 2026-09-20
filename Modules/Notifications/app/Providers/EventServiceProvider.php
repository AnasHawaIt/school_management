<?php

namespace Modules\Notifications\app\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Modules\Notifications\app\Events\NotificationCreated;
use Modules\Notifications\app\Listeners\NotificationCreatedLogEventListener;
use Modules\Notifications\app\Listeners\SendFirebaseNotification;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     */
    protected $listen = [
        NotificationCreated::class => [
            SendFirebaseNotification::class,
            NotificationCreatedLogEventListener::class,
        ],
    ];

    public function boot(): void
    {
        parent::boot();
    }
}
