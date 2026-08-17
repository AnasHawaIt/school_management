<?php

namespace Modules\School\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Modules\School\Events\SectionCreated;
use Modules\School\Events\SectionDeleted;
use Modules\School\Events\SectionUpdated;
use Modules\School\Listeners\LogSectionCreated;
use Modules\School\Listeners\LogSectionDeleted;
use Modules\School\Listeners\LogSectionUpdated;
use Modules\School\Listeners\SectionCreatedNotificationDatabaseListener;
use Modules\School\Listeners\SectionDeletedNotificationDatabaseListener;
use Modules\School\Listeners\SectionUpdatedNotificationDatabaseListener;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event handler mappings for the application.
     *
     * @var array<string, array<int, string>>
     */
    protected $listen = [
        SectionCreated::class => [
            LogSectionCreated::class,
            SectionCreatedNotificationDatabaseListener::class,
        ],

        SectionUpdated::class => [
            LogSectionUpdated::class,
            SectionUpdatedNotificationDatabaseListener::class,
        ],

        SectionDeleted::class => [
            LogSectionDeleted::class,
            SectionDeletedNotificationDatabaseListener::class,
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
