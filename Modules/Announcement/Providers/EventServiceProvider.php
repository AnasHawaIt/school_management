<?php

namespace Modules\Announcement\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Modules\Announcement\Events\AnnouncementCreated;
use Modules\Announcement\Events\AnnouncementDeleted;
use Modules\Announcement\Events\AnnouncementUpdated;
use Modules\Messagings\Listeners\MessageSendedListener\AnnouncementCreatedBroadcastEventListener;
use Modules\Messagings\Listeners\MessageSendedListener\AnnouncementCreatedLogEventListener;
use Modules\Messagings\Listeners\MessageSendedListener\SedNotificationDatabaseListener;
use Modules\Messagings\Listeners\MessageDeletedListener\AnnouncementDeletedBroadcastEventListener;
use Modules\Messagings\Listeners\MessageDeletedListener\AnnouncementDeletedLogEventListener;
use Modules\Messagings\Listeners\MessageDeletedListener\AnnouncementDeletedNotificationDatabaseListener;
use Modules\Messagings\Listeners\MessageUpdatedListener\AnnouncementUpdatedBroadcastEventListener;
use Modules\Messagings\Listeners\MessageUpdatedListener\AnnouncementUpdatedLogEventListener;
use Modules\Messagings\Listeners\MessageUpdatedListener\AnnouncementUpdatedNotificationDatabaseListener;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event handler mappings for the application.
     *
     * @var array<string, array<int, string>>
     */
    protected $listen = [

        AnnouncementCreated::class => [
            AnnouncementCreatedLogEventListener::class,
            SedNotificationDatabaseListener::class,
            AnnouncementCreatedBroadcastEventListener::class,
        ],

        AnnouncementUpdated::class => [
            AnnouncementUpdatedLogEventListener::class,
            AnnouncementUpdatedNotificationDatabaseListener::class,
            AnnouncementUpdatedBroadcastEventListener::class,
        ],

        AnnouncementDeleted::class => [
            AnnouncementDeletedLogEventListener::class,
            AnnouncementDeletedNotificationDatabaseListener::class,
            AnnouncementDeletedBroadcastEventListener::class,
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
