<?php

namespace Modules\Announcement\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Modules\Announcement\Events\AnnouncementCreated;
use Modules\Announcement\Events\AnnouncementDeleted;
use Modules\Announcement\Events\AnnouncementExpired;
use Modules\Announcement\Events\AnnouncementPublished;
use Modules\Announcement\Events\AnnouncementRestored;
use Modules\Announcement\Events\AnnouncementScheduled;
use Modules\Announcement\Events\AnnouncementUpdated;
use Modules\Announcement\Listeners\AnnouncementCreatedListener\AnnouncementCreatedBroadcastEventListener;
use Modules\Announcement\Listeners\AnnouncementCreatedListener\AnnouncementCreatedLogEventListener;
use Modules\Announcement\Listeners\AnnouncementDeletedListener\AnnouncementDeletedBroadcastEventListener;
use Modules\Announcement\Listeners\AnnouncementDeletedListener\AnnouncementDeletedLogEventListener;
use Modules\Announcement\Listeners\AnnouncementExpiredListener\AnnouncementExpiredBroadcastEventListener;
use Modules\Announcement\Listeners\AnnouncementExpiredListener\AnnouncementExpiredLogEventListener;
use Modules\Announcement\Listeners\AnnouncementPublishedListener\AnnouncementPublishedBroadcastEventListener;
use Modules\Announcement\Listeners\AnnouncementPublishedListener\AnnouncementPublishedLogEventListener;
use Modules\Announcement\Listeners\AnnouncementPublishedListener\AnnouncementPublishedNotificationDatabaseListener;
use Modules\Announcement\Listeners\AnnouncementRestoredListener\AnnouncementRestoredBroadcastEventListener;
use Modules\Announcement\Listeners\AnnouncementRestoredListener\AnnouncementRestoredLogEventListener;
use Modules\Announcement\Listeners\AnnouncementScheduledListener\AnnouncementScheduledBroadcastEventListener;
use Modules\Announcement\Listeners\AnnouncementScheduledListener\AnnouncementScheduledLogEventListener;
use Modules\Announcement\Listeners\AnnouncementUpdatedListener\AnnouncementUpdatedBroadcastEventListener;
use Modules\Announcement\Listeners\AnnouncementUpdatedListener\AnnouncementUpdatedLogEventListener;


class EventServiceProvider extends ServiceProvider
{
    /**
     * The event handler mappings for the application.
     *
     * @var array<string, array<int, string>>
     */
    protected $listen = [

        /*
        |--------------------------------------------------------------------------
        | Announcement Created
        |--------------------------------------------------------------------------
        */
        AnnouncementCreated::class => [
            AnnouncementCreatedBroadcastEventListener::class,
            AnnouncementCreatedLogEventListener::class,
        ],

        /*
        |--------------------------------------------------------------------------
        | Announcement Updated
        |--------------------------------------------------------------------------
        */
        AnnouncementUpdated::class => [
            AnnouncementUpdatedBroadcastEventListener::class,
            AnnouncementUpdatedLogEventListener::class,
        ],

        /*
        |--------------------------------------------------------------------------
        | Announcement Deleted
        |--------------------------------------------------------------------------
        */
        AnnouncementDeleted::class => [
            AnnouncementDeletedBroadcastEventListener::class,
            AnnouncementDeletedLogEventListener::class,
        ],

        /*
        |--------------------------------------------------------------------------
        | Announcement Restored
        |--------------------------------------------------------------------------
        */
        AnnouncementRestored::class => [
            AnnouncementRestoredBroadcastEventListener::class,
            AnnouncementRestoredLogEventListener::class,
        ],

        /*
        |--------------------------------------------------------------------------
        | Announcement Published
        |--------------------------------------------------------------------------
        */
        AnnouncementPublished::class => [
            AnnouncementPublishedBroadcastEventListener::class,
            AnnouncementPublishedLogEventListener::class,
            AnnouncementPublishedNotificationDatabaseListener::class,
        ],

        /*
        |--------------------------------------------------------------------------
        | Announcement Scheduled
        |--------------------------------------------------------------------------
        */
        AnnouncementScheduled::class => [
            AnnouncementScheduledBroadcastEventListener::class,
            AnnouncementScheduledLogEventListener::class,
        ],

        /*
        |--------------------------------------------------------------------------
        | Announcement Expired
        |--------------------------------------------------------------------------
        */
        AnnouncementExpired::class => [
            AnnouncementExpiredBroadcastEventListener::class,
            AnnouncementExpiredLogEventListener::class,
        ],

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
