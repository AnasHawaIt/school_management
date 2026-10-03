<?php

namespace Modules\Notifications\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Modules\Notifications\Events\NotificationCreated;
use Modules\Notifications\Listeners\NotificationCreatedLogEventListener;
use Modules\Notifications\Listeners\SendFirebaseNotification;

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
