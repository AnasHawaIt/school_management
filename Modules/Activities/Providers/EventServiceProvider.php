<?php

namespace Modules\Activities\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Modules\Activities\Events\ActivityAttachmentDeleted;
use Modules\Activities\Events\ActivityAttachmentUploaded;
use Modules\Activities\Events\ActivityCancelled;
use Modules\Activities\Events\ActivityCompleted;
use Modules\Activities\Events\ActivityCreated;
use Modules\Activities\Events\ActivityParticipantAbsent;
use Modules\Activities\Events\ActivityParticipantAttended;
use Modules\Activities\Events\ActivityParticipantCancelled;
use Modules\Activities\Events\ActivityParticipantConfirmed;
use Modules\Activities\Events\ActivityParticipantRegistered;
use Modules\Activities\Events\ActivityPrimarySupervisorChanged;
use Modules\Activities\Events\ActivityPublished;
use Modules\Activities\Events\ActivityStarted;
use Modules\Activities\Events\ActivitySupervisorAdded;
use Modules\Activities\Events\ActivitySupervisorRemoved;
use Modules\Activities\Events\ActivityUpdated;
use Modules\Activities\Listeners\LogActivityAttachmentDeleted;
use Modules\Activities\Listeners\LogActivityAttachmentUploaded;
use Modules\Activities\Listeners\LogActivityCancelled;
use Modules\Activities\Listeners\LogActivityCompleted;
use Modules\Activities\Listeners\LogActivityCreated;
use Modules\Activities\Listeners\LogActivityParticipantAbsent;
use Modules\Activities\Listeners\LogActivityParticipantAttended;
use Modules\Activities\Listeners\LogActivityParticipantCancelled;
use Modules\Activities\Listeners\LogActivityParticipantConfirmed;
use Modules\Activities\Listeners\LogActivityParticipantRegistered;
use Modules\Activities\Listeners\LogActivityPrimarySupervisorChanged;
use Modules\Activities\Listeners\LogActivityPublished;
use Modules\Activities\Listeners\LogActivityStarted;
use Modules\Activities\Listeners\LogActivitySupervisorAdded;
use Modules\Activities\Listeners\LogActivitySupervisorRemoved;
use Modules\Activities\Listeners\LogActivityUpdated;

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
        | Activity
        |--------------------------------------------------------------------------
        */

        ActivityCreated::class => [
            LogActivityCreated::class,
        ],

        ActivityUpdated::class => [
            LogActivityUpdated::class,
        ],

        ActivityPublished::class => [
            LogActivityPublished::class,
        ],

        ActivityStarted::class => [
            LogActivityStarted::class,
        ],

        ActivityCompleted::class => [
            LogActivityCompleted::class,
        ],

        ActivityCancelled::class => [
            LogActivityCancelled::class,
        ],


        /*
        |--------------------------------------------------------------------------
        | Participants
        |--------------------------------------------------------------------------
        */

        ActivityParticipantRegistered::class => [
            LogActivityParticipantRegistered::class,
        ],

        ActivityParticipantConfirmed::class => [
            LogActivityParticipantConfirmed::class,
        ],

        ActivityParticipantCancelled::class => [
            LogActivityParticipantCancelled::class,
        ],

        ActivityParticipantAttended::class => [
            LogActivityParticipantAttended::class,
        ],

        ActivityParticipantAbsent::class => [
            LogActivityParticipantAbsent::class,
        ],


        /*
        |--------------------------------------------------------------------------
        | Supervisors
        |--------------------------------------------------------------------------
        */

        ActivitySupervisorAdded::class => [
            LogActivitySupervisorAdded::class,
        ],

        ActivitySupervisorRemoved::class => [
            LogActivitySupervisorRemoved::class,
        ],

        ActivityPrimarySupervisorChanged::class => [
            LogActivityPrimarySupervisorChanged::class,
        ],


        /*
        |--------------------------------------------------------------------------
        | Attachments
        |--------------------------------------------------------------------------
        */

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
