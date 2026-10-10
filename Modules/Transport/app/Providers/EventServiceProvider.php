<?php

namespace Modules\Transport\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Modules\Transport\Events\BusEvents\BusCreated;
use Modules\Transport\Events\BusEvents\BusDeleted;
use Modules\Transport\Events\BusEvents\BusStopStageChanged;
use Modules\Transport\Events\BusEvents\BusUpdated;
use Modules\Transport\Events\RouteEvents\RouteCreated;
use Modules\Transport\Events\RouteEvents\RouteDeleted;
use Modules\Transport\Events\RouteEvents\RouteUpdated;
use Modules\Transport\Events\RouteStopEvents\RouteStopCreated;
use Modules\Transport\Events\RouteStopEvents\RouteStopDeleted;
use Modules\Transport\Events\RouteStopEvents\RouteStopUpdated;
use Modules\Transport\Events\SubscriptionEvents\SubscriptionCreated;
use Modules\Transport\Events\SubscriptionEvents\SubscriptionDeleted;
use Modules\Transport\Events\SubscriptionEvents\SubscriptionUpdated;
use Modules\Transport\Listeners\BusListeners\BusCreatedListeners\BusCreatedBroadcastEventListener;
use Modules\Transport\Listeners\BusListeners\BusCreatedListeners\BusCreatedNotificationDatabaseListener;
use Modules\Transport\Listeners\BusListeners\BusDeletedListeners\BusDeletedBroadcastEventListener;
use Modules\Transport\Listeners\BusListeners\BusStopStageChangedListener;
use Modules\Transport\Listeners\BusListeners\BusUpdateListeners\BusUpdateBroadcastEventListener;
use Modules\Transport\Listeners\RouteListeners\RouteCreatedNotificationDatabaseListener;
use Modules\Transport\Listeners\SubscriptionListeners\SubscriptionCreatedListener\SubscriptionCreatedBroadcastEventListener;
use Modules\Transport\Listeners\SubscriptionListeners\SubscriptionCreatedListener\SubscriptionCreatedNotificationDatabaseListener;
use Modules\Transport\Listeners\SubscriptionListeners\SubscriptionDeletedListener\SubscriptionDeletedNotificationDatabaseListener;
use Modules\Transport\Listeners\SubscriptionListeners\SubscriptionUpdatedListener\SubscriptionUpdatedBroadcastEventListener;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event handler mappings for the application.
     *
     * @var array<string, array<int, string>>
     */
    protected $listen = [
        BusCreated::class => [
            BusCreatedNotificationDatabaseListener::class,
            BusCreatedBroadcastEventListener::class,
        ],

        BusUpdated::class => [
            BusUpdateBroadcastEventListener::class,
        ],

        BusDeleted::class => [
            BusDeletedBroadcastEventListener::class,
        ],

        BusStopStageChanged::class => [
            BusStopStageChangedListener::class,
        ],

        RouteCreated::class => [
            RouteCreatedNotificationDatabaseListener::class,
        ],

        RouteUpdated::class => [],

        RouteDeleted::class => [],

        RouteStopCreated::class => [],

        RouteStopUpdated::class => [],

        RouteStopDeleted::class => [],

        SubscriptionCreated::class => [
            SubscriptionCreatedNotificationDatabaseListener::class,
            SubscriptionCreatedBroadcastEventListener::class,
        ],

        SubscriptionUpdated::class => [
            SubscriptionUpdatedBroadcastEventListener::class,
        ],

        SubscriptionDeleted::class => [
            SubscriptionDeletedNotificationDatabaseListener::class,
        ]
    ];

    /**
     * Indicates if events should be discovered.
     *
     * @var bool
     */
    protected static $shouldDiscoverEvents = true;

    /**
     * Configure the proper event listeners for email verification.
     */
    protected function configureEmailVerification(): void {}
}
