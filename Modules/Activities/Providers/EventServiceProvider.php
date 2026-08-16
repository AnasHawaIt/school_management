<?php

namespace Modules\Activities\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Modules\Activities\Events\ActivityAttachmentDeleted;
use Modules\Activities\Events\ActivityAttachmentUploaded;
use Modules\Activities\Events\ActivityPrimarySupervisorChanged;
use Modules\Activities\Events\ActivitySupervisorAdded;
use Modules\Activities\Events\ActivitySupervisorRemoved;
use Modules\Activities\Listeners\LogActivityAttachmentDeleted;
use Modules\Activities\Listeners\LogActivityAttachmentUploaded;
use Modules\Activities\Listeners\LogActivityPrimarySupervisorChanged;
use Modules\Activities\Listeners\LogActivitySupervisorAdded;
use Modules\Activities\Listeners\LogActivitySupervisorRemoved;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event handler mappings for the application.
     *
     * @var array<string, array<int, string>>
     */
    protected $listen = [

        ActivitySupervisorAdded::class => [
            LogActivitySupervisorAdded::class,
        ],

        ActivitySupervisorRemoved::class => [
            LogActivitySupervisorRemoved::class,
        ],

        ActivityPrimarySupervisorChanged::class => [
            LogActivityPrimarySupervisorChanged::class,
        ],

        ActivityAttachmentUploaded::class => [
            LogActivityAttachmentUploaded::class,
        ],

        ActivityAttachmentDeleted::class => [
            LogActivityAttachmentDeleted::class,
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
