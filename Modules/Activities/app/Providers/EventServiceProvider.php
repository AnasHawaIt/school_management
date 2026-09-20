<?php

namespace Modules\Activities\app\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Modules\Activities\app\Events\ActivityAttachmentDeleted;
use Modules\Activities\app\Events\ActivityAttachmentUploaded;
use Modules\Activities\app\Events\ActivityCancelled;
use Modules\Activities\app\Events\ActivityCompleted;
use Modules\Activities\app\Events\ActivityCreated;
use Modules\Activities\app\Events\ActivityParticipantAbsent;
use Modules\Activities\app\Events\ActivityParticipantAttended;
use Modules\Activities\app\Events\ActivityParticipantCancelled;
use Modules\Activities\app\Events\ActivityParticipantConfirmed;
use Modules\Activities\app\Events\ActivityParticipantRegistered;
use Modules\Activities\app\Events\ActivityPrimarySupervisorChanged;
use Modules\Activities\app\Events\ActivityPublished;
use Modules\Activities\app\Events\ActivityStarted;
use Modules\Activities\app\Events\ActivitySupervisorAdded;
use Modules\Activities\app\Events\ActivitySupervisorRemoved;
use Modules\Activities\app\Events\ActivityUpdated;
use Modules\Activities\app\Listeners\Activity\ActivityCancelledNotificationListener;
use Modules\Activities\app\Listeners\Activity\ActivityCompletedNotificationListener;
use Modules\Activities\app\Listeners\Activity\ActivityCreatedNotificationListener;
use Modules\Activities\app\Listeners\Activity\ActivityPublishedNotificationListener;
use Modules\Activities\app\Listeners\Activity\ActivityStartedNotificationListener;
use Modules\Activities\app\Listeners\Activity\ActivityUpdatedNotificationListener;
use Modules\Activities\app\Listeners\Activity\LogActivityCancelled;
use Modules\Activities\app\Listeners\Activity\LogActivityCompleted;
use Modules\Activities\app\Listeners\Activity\LogActivityCreated;
use Modules\Activities\app\Listeners\Activity\LogActivityPublished;
use Modules\Activities\app\Listeners\Activity\LogActivityStarted;
use Modules\Activities\app\Listeners\Activity\LogActivityUpdated;
use Modules\Activities\app\Listeners\Attachment\ActivityAttachmentDeletedNotificationDatabaseListener;
use Modules\Activities\app\Listeners\Attachment\ActivityAttachmentUploadedNotificationDatabaseListener;
use Modules\Activities\app\Listeners\Attachment\LogActivityAttachmentDeleted;
use Modules\Activities\app\Listeners\Attachment\LogActivityAttachmentUploaded;
use Modules\Activities\app\Listeners\Participant\ActivityParticipantAbsentNotificationDatabaseListener;
use Modules\Activities\app\Listeners\Participant\ActivityParticipantAttendedNotificationDatabaseListener;
use Modules\Activities\app\Listeners\Participant\ActivityParticipantCancelledNotificationDatabaseListener;
use Modules\Activities\app\Listeners\Participant\ActivityParticipantConfirmedNotificationDatabaseListener;
use Modules\Activities\app\Listeners\Participant\ActivityParticipantRegisteredNotificationDatabaseListener;
use Modules\Activities\app\Listeners\Participant\LogActivityParticipantAbsent;
use Modules\Activities\app\Listeners\Participant\LogActivityParticipantAttended;
use Modules\Activities\app\Listeners\Participant\LogActivityParticipantCancelled;
use Modules\Activities\app\Listeners\Participant\LogActivityParticipantConfirmed;
use Modules\Activities\app\Listeners\Participant\LogActivityParticipantRegistered;
use Modules\Activities\app\Listeners\Supervisor\ActivityPrimarySupervisorChangedNotificationDatabaseListener;
use Modules\Activities\app\Listeners\Supervisor\ActivitySupervisorAddedNotificationDatabaseListener;
use Modules\Activities\app\Listeners\Supervisor\ActivitySupervisorRemovedNotificationDatabaseListener;
use Modules\Activities\app\Listeners\Supervisor\LogActivityPrimarySupervisorChanged;
use Modules\Activities\app\Listeners\Supervisor\LogActivitySupervisorAdded;
use Modules\Activities\app\Listeners\Supervisor\LogActivitySupervisorRemoved;

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
            ActivityCreatedNotificationListener::class
        ],

        ActivityUpdated::class => [
            LogActivityUpdated::class,
            ActivityUpdatedNotificationListener::class
        ],

        ActivityPublished::class => [
            LogActivityPublished::class,
            ActivityPublishedNotificationListener::class
        ],

        ActivityStarted::class => [
            LogActivityStarted::class,
            ActivityStartedNotificationListener::class
        ],

        ActivityCompleted::class => [
            LogActivityCompleted::class,
            ActivityCompletedNotificationListener::class
        ],

        ActivityCancelled::class => [
            LogActivityCancelled::class,
            ActivityCancelledNotificationListener::class
        ],


        /*
        |--------------------------------------------------------------------------
        | Participants
        |--------------------------------------------------------------------------
        */

        ActivityParticipantRegistered::class => [
            LogActivityParticipantRegistered::class,
            ActivityParticipantRegisteredNotificationDatabaseListener::class
        ],

        ActivityParticipantConfirmed::class => [
            LogActivityParticipantConfirmed::class,
            ActivityParticipantConfirmedNotificationDatabaseListener::class
        ],

        ActivityParticipantCancelled::class => [
            LogActivityParticipantCancelled::class,
            ActivityParticipantCancelledNotificationDatabaseListener::class
        ],

        ActivityParticipantAttended::class => [
            LogActivityParticipantAttended::class,
            ActivityParticipantAttendedNotificationDatabaseListener::class
        ],

        ActivityParticipantAbsent::class => [
            LogActivityParticipantAbsent::class,
            ActivityParticipantAbsentNotificationDatabaseListener::class
        ],


        /*
        |--------------------------------------------------------------------------
        | Supervisors
        |--------------------------------------------------------------------------
        */

        ActivitySupervisorAdded::class => [
            LogActivitySupervisorAdded::class,
            ActivitySupervisorAddedNotificationDatabaseListener::class
        ],

        ActivitySupervisorRemoved::class => [
            LogActivitySupervisorRemoved::class,
            ActivitySupervisorRemovedNotificationDatabaseListener::class
        ],

        ActivityPrimarySupervisorChanged::class => [
            LogActivityPrimarySupervisorChanged::class,
            ActivityPrimarySupervisorChangedNotificationDatabaseListener::class
        ],


        /*
        |--------------------------------------------------------------------------
        | Attachments
        |--------------------------------------------------------------------------
        */

        ActivityAttachmentUploaded::class => [
            LogActivityAttachmentUploaded::class,
            ActivityAttachmentUploadedNotificationDatabaseListener::class
        ],

        ActivityAttachmentDeleted::class => [
            LogActivityAttachmentDeleted::class,
            ActivityAttachmentDeletedNotificationDatabaseListener::class
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
