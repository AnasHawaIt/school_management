<?php

namespace Modules\Examination\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Modules\Examination\Events\ExamCreated;
use Modules\Examination\Events\ExamDeleted;
use Modules\Examination\Events\ExamRestored;
use Modules\Examination\Events\ExamStatusUpdated;
use Modules\Examination\Events\ExamUpdated;
use Modules\Examination\Listeners\ExamCreatedNotificationDatabaseListener;
use Modules\Examination\Listeners\ExamDeletedNotificationDatabaseListener;
use Modules\Examination\Listeners\ExamStatusUpdatedNotificationDatabaseListener;
use Modules\Examination\Listeners\ExamUpdatedNotificationDatabaseListener;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event handler mappings for the application.
     *
     * @var array<string, array<int, string>>
     */
    protected $listen = [
        ExamCreated::class => [
            ExamCreatedNotificationDatabaseListener::class,
        ],

        ExamDeleted::class => [
            ExamDeletedNotificationDatabaseListener::class,
        ],

        ExamUpdated::class => [
            ExamUpdatedNotificationDatabaseListener::class,
        ],

        ExamRestored::class => [
        ],

        ExamStatusUpdated::class => [
            ExamStatusUpdatedNotificationDatabaseListener::class,
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
