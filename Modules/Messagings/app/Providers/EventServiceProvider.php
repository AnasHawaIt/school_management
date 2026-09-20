<?php

namespace Modules\Messagings\app\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Modules\Messagings\app\Events\AdminDemoted;
use Modules\Messagings\app\Events\AdminPromoted;
use Modules\Messagings\app\Events\AttachmentDeleted;
use Modules\Messagings\app\Events\AttachmentUploaded;
use Modules\Messagings\app\Events\ConversationCreated;
use Modules\Messagings\app\Events\ConversationDeleted;
use Modules\Messagings\app\Events\Message\MessageCreated;
use Modules\Messagings\app\Events\Message\MessageDeleted;
use Modules\Messagings\app\Events\Message\MessageForwarded;
use Modules\Messagings\app\Events\Message\MessageRead;
use Modules\Messagings\app\Events\Message\MessageReplied;
use Modules\Messagings\app\Events\Message\MessageRestored;
use Modules\Messagings\app\Events\Message\TypingStarted;
use Modules\Messagings\app\Events\Message\TypingStopped;
use Modules\Messagings\app\Events\ParticipantAdded;
use Modules\Messagings\app\Events\ParticipantLeft;
use Modules\Messagings\app\Events\ParticipantRemoved;
use Modules\Messagings\app\Listeners\Broadcasting\BroadcastAdminDemoted;
use Modules\Messagings\app\Listeners\Broadcasting\BroadcastAdminPromoted;
use Modules\Messagings\app\Listeners\Broadcasting\BroadcastAttachmentDeleted;
use Modules\Messagings\app\Listeners\Broadcasting\BroadcastAttachmentUploaded;
use Modules\Messagings\app\Listeners\Broadcasting\BroadcastConversationCreated;
use Modules\Messagings\app\Listeners\Broadcasting\BroadcastConversationDeleted;
use Modules\Messagings\app\Listeners\Broadcasting\BroadcastMessageCreated;
use Modules\Messagings\app\Listeners\Broadcasting\BroadcastMessageDeleted;
use Modules\Messagings\app\Listeners\Broadcasting\BroadcastMessageForwarded;
use Modules\Messagings\app\Listeners\Broadcasting\BroadcastMessageRead;
use Modules\Messagings\app\Listeners\Broadcasting\BroadcastMessageReplied;
use Modules\Messagings\app\Listeners\Broadcasting\BroadcastMessageRestored;
use Modules\Messagings\app\Listeners\Broadcasting\BroadcastParticipantAdded;
use Modules\Messagings\app\Listeners\Broadcasting\BroadcastParticipantLeft;
use Modules\Messagings\app\Listeners\Broadcasting\BroadcastParticipantRemoved;
use Modules\Messagings\app\Listeners\Broadcasting\BroadcastTypingStarted;
use Modules\Messagings\app\Listeners\Broadcasting\BroadcastTypingStopped;
use Modules\Messagings\app\Listeners\Log\LogAdminDemotedEventListener;
use Modules\Messagings\app\Listeners\Log\LogAdminPromotedEventListener;
use Modules\Messagings\app\Listeners\Log\LogAttachmentDeletedEventListener;
use Modules\Messagings\app\Listeners\Log\LogAttachmentUploadedEventListener;
use Modules\Messagings\app\Listeners\Log\LogConversationCreatedEventListener;
use Modules\Messagings\app\Listeners\Log\LogConversationDeletedEventListener;
use Modules\Messagings\app\Listeners\Log\LogMessageCreatedEventListener;
use Modules\Messagings\app\Listeners\Log\LogMessageDeletedEventListener;
use Modules\Messagings\app\Listeners\Log\LogMessageForwardedEventListener;
use Modules\Messagings\app\Listeners\Log\LogMessageReadEventListener;
use Modules\Messagings\app\Listeners\Log\LogMessageRepliedEventListener;
use Modules\Messagings\app\Listeners\Log\LogMessageRestoredEventListener;
use Modules\Messagings\app\Listeners\Log\LogParticipantAddedEventListener;
use Modules\Messagings\app\Listeners\Log\LogParticipantLeftEventListener;
use Modules\Messagings\app\Listeners\Log\LogParticipantRemovedEventListener;
use Modules\Messagings\app\Listeners\SendAdminDemotedNotificationListener;
use Modules\Messagings\app\Listeners\SendAdminPromotedNotificationListener;
use Modules\Messagings\app\Listeners\SendEmailListener;
use Modules\Messagings\app\Listeners\SendMessageNotificationListener;
use Modules\Messagings\app\Listeners\SendParticipantAddedNotificationListener;
use Modules\Messagings\app\Listeners\SendParticipantLeftNotificationListener;
use Modules\Messagings\app\Listeners\SendParticipantRemovedNotificationListener;
use Modules\Messagings\app\Listeners\StoreMessageStatisticsListener;
use Modules\Messagings\app\Listeners\UpdateMessageForwardStatistic;
use Modules\Messagings\app\Listeners\UpdateMessageReplyStatistic;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event handler mappings for the application.
     *
     * @var array<string, array<int, string>>
     */
    protected $listen = [

            // =========================
            // CONVERSATION
            // =========================

            ConversationCreated::class => [
                LogConversationCreatedEventListener::class,
                BroadcastConversationCreated::class
            ],

            ConversationDeleted::class => [
                LogConversationDeletedEventListener::class,
                BroadcastConversationDeleted::class
            ],

            // =========================
            // PARTICIPANTS
            // =========================

            ParticipantAdded::class => [
                LogParticipantAddedEventListener::class,
                SendParticipantAddedNotificationListener::class,
                BroadcastParticipantAdded::class,
            ],

            ParticipantRemoved::class => [
                LogParticipantRemovedEventListener::class,
                SendParticipantRemovedNotificationListener::class,
                BroadcastParticipantRemoved::class,
            ],

            ParticipantLeft::class => [
                LogParticipantLeftEventListener::class,
                SendParticipantLeftNotificationListener::class,
                BroadcastParticipantLeft::class,
            ],

            // =========================
            // ADMIN
            // =========================

            AdminPromoted::class => [
                LogAdminPromotedEventListener::class,
                SendAdminPromotedNotificationListener::class,
                BroadcastAdminPromoted::class,
            ],

            AdminDemoted::class => [
                LogAdminDemotedEventListener::class,
                SendAdminDemotedNotificationListener::class,
                BroadcastAdminDemoted::class,
            ],


            // =========================
            // MESSAGES
            // =========================

            MessageCreated::class => [
                LogMessageCreatedEventListener::class,
                StoreMessageStatisticsListener::class,
                SendMessageNotificationListener::class,
                SendEmailListener::class,
                BroadcastMessageCreated::class,
            ],

            MessageDeleted::class => [
                LogMessageDeletedEventListener::class,
                BroadcastMessageDeleted::class,
            ],

            MessageForwarded::class => [
                LogMessageForwardedEventListener::class,
                UpdateMessageForwardStatistic::class,
                BroadcastMessageForwarded::class,
            ],

            MessageRead::class => [
                LogMessageReadEventListener::class,
                BroadcastMessageRead::class,
            ],

            MessageReplied::class => [
                LogMessageRepliedEventListener::class,
                UpdateMessageReplyStatistic::class,
                BroadcastMessageReplied::class,
            ],

            MessageRestored::class => [
                LogMessageRestoredEventListener::class,
                BroadcastMessageRestored::class,
            ],

            // =========================
            // ATTACHMENTS
            // =========================


            AttachmentUploaded::class => [
                LogAttachmentUploadedEventListener::class,
                BroadcastAttachmentUploaded::class,
            ],

            AttachmentDeleted::class => [
                LogAttachmentDeletedEventListener::class,
                BroadcastAttachmentDeleted::class,
            ],

            TypingStarted::class => [
                BroadcastTypingStarted::class,
            ],

            TypingStopped::class => [
                BroadcastTypingStopped::class,
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
