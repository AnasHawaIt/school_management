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
use Modules\Activities\Listeners\Activity\ActivityCancelledNotificationListener;
use Modules\Activities\Listeners\Activity\ActivityCompletedNotificationListener;
use Modules\Activities\Listeners\Activity\ActivityCreatedNotificationListener;
use Modules\Activities\Listeners\Activity\ActivityPublishedNotificationListener;
use Modules\Activities\Listeners\Activity\ActivityStartedNotificationListener;
use Modules\Activities\Listeners\Activity\ActivityUpdatedNotificationListener;
use Modules\Activities\Listeners\Activity\LogActivityCancelled;
use Modules\Activities\Listeners\Activity\LogActivityCompleted;
use Modules\Activities\Listeners\Activity\LogActivityCreated;
use Modules\Activities\Listeners\Activity\LogActivityPublished;
use Modules\Activities\Listeners\Activity\LogActivityStarted;
use Modules\Activities\Listeners\Activity\LogActivityUpdated;
use Modules\Activities\Listeners\Attachment\ActivityAttachmentDeletedNotificationDatabaseListener;
use Modules\Activities\Listeners\Attachment\ActivityAttachmentUploadedNotificationDatabaseListener;
use Modules\Activities\Listeners\Attachment\LogActivityAttachmentDeleted;
use Modules\Activities\Listeners\Attachment\LogActivityAttachmentUploaded;
use Modules\Activities\Listeners\Participant\ActivityParticipantAbsentNotificationDatabaseListener;
use Modules\Activities\Listeners\Participant\ActivityParticipantAttendedNotificationDatabaseListener;
use Modules\Activities\Listeners\Participant\ActivityParticipantCancelledNotificationDatabaseListener;
use Modules\Activities\Listeners\Participant\ActivityParticipantConfirmedNotificationDatabaseListener;
use Modules\Activities\Listeners\Participant\ActivityParticipantRegisteredNotificationDatabaseListener;
use Modules\Activities\Listeners\Participant\LogActivityParticipantAbsent;
use Modules\Activities\Listeners\Participant\LogActivityParticipantAttended;
use Modules\Activities\Listeners\Participant\LogActivityParticipantCancelled;
use Modules\Activities\Listeners\Participant\LogActivityParticipantConfirmed;
use Modules\Activities\Listeners\Participant\LogActivityParticipantRegistered;
use Modules\Activities\Listeners\Supervisor\ActivityPrimarySupervisorChangedNotificationDatabaseListener;
use Modules\Activities\Listeners\Supervisor\ActivitySupervisorAddedNotificationDatabaseListener;
use Modules\Activities\Listeners\Supervisor\ActivitySupervisorRemovedNotificationDatabaseListener;
use Modules\Activities\Listeners\Supervisor\LogActivityPrimarySupervisorChanged;
use Modules\Activities\Listeners\Supervisor\LogActivitySupervisorAdded;
use Modules\Activities\Listeners\Supervisor\LogActivitySupervisorRemoved;

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
