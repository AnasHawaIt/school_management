<?php

namespace Modules\Messagings\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Modules\Messagings\Events\AdminDemoted;
use Modules\Messagings\Events\AdminPromoted;
use Modules\Messagings\Events\AttachmentDeleted;
use Modules\Messagings\Events\AttachmentUploaded;
use Modules\Messagings\Events\ConversationCreated;
use Modules\Messagings\Events\ConversationDeleted;
use Modules\Messagings\Events\Message\MessageCreated;
use Modules\Messagings\Events\Message\MessageDeleted;
use Modules\Messagings\Events\Message\MessageForwarded;
use Modules\Messagings\Events\Message\MessageRead;
use Modules\Messagings\Events\Message\MessageReplied;
use Modules\Messagings\Events\Message\MessageRestored;
use Modules\Messagings\Events\Message\TypingStarted;
use Modules\Messagings\Events\Message\TypingStopped;
use Modules\Messagings\Events\ParticipantAdded;
use Modules\Messagings\Events\ParticipantLeft;
use Modules\Messagings\Events\ParticipantRemoved;
use Modules\Messagings\Listeners\Broadcasting\BroadcastAdminDemoted;
use Modules\Messagings\Listeners\Broadcasting\BroadcastAdminPromoted;
use Modules\Messagings\Listeners\Broadcasting\BroadcastAttachmentDeleted;
use Modules\Messagings\Listeners\Broadcasting\BroadcastAttachmentUploaded;
use Modules\Messagings\Listeners\Broadcasting\BroadcastConversationCreated;
use Modules\Messagings\Listeners\Broadcasting\BroadcastConversationDeleted;
use Modules\Messagings\Listeners\Broadcasting\BroadcastMessageCreated;
use Modules\Messagings\Listeners\Broadcasting\BroadcastMessageDeleted;
use Modules\Messagings\Listeners\Broadcasting\BroadcastMessageForwarded;
use Modules\Messagings\Listeners\Broadcasting\BroadcastMessageRead;
use Modules\Messagings\Listeners\Broadcasting\BroadcastMessageReplied;
use Modules\Messagings\Listeners\Broadcasting\BroadcastMessageRestored;
use Modules\Messagings\Listeners\Broadcasting\BroadcastParticipantAdded;
use Modules\Messagings\Listeners\Broadcasting\BroadcastParticipantLeft;
use Modules\Messagings\Listeners\Broadcasting\BroadcastParticipantRemoved;
use Modules\Messagings\Listeners\Broadcasting\BroadcastTypingStarted;
use Modules\Messagings\Listeners\Broadcasting\BroadcastTypingStopped;
use Modules\Messagings\Listeners\Log\LogAdminDemotedEventListener;
use Modules\Messagings\Listeners\Log\LogAdminPromotedEventListener;
use Modules\Messagings\Listeners\Log\LogAttachmentDeletedEventListener;
use Modules\Messagings\Listeners\Log\LogAttachmentUploadedEventListener;
use Modules\Messagings\Listeners\Log\LogConversationCreatedEventListener;
use Modules\Messagings\Listeners\Log\LogConversationDeletedEventListener;
use Modules\Messagings\Listeners\Log\LogMessageCreatedEventListener;
use Modules\Messagings\Listeners\Log\LogMessageDeletedEventListener;
use Modules\Messagings\Listeners\Log\LogMessageForwardedEventListener;
use Modules\Messagings\Listeners\Log\LogMessageReadEventListener;
use Modules\Messagings\Listeners\Log\LogMessageRepliedEventListener;
use Modules\Messagings\Listeners\Log\LogMessageRestoredEventListener;
use Modules\Messagings\Listeners\Log\LogParticipantAddedEventListener;
use Modules\Messagings\Listeners\Log\LogParticipantLeftEventListener;
use Modules\Messagings\Listeners\Log\LogParticipantRemovedEventListener;
use Modules\Messagings\Listeners\SendAdminDemotedNotificationListener;
use Modules\Messagings\Listeners\SendAdminPromotedNotificationListener;
use Modules\Messagings\Listeners\SendEmailListener;
use Modules\Messagings\Listeners\SendMessageNotificationListener;
use Modules\Messagings\Listeners\SendParticipantAddedNotificationListener;
use Modules\Messagings\Listeners\SendParticipantLeftNotificationListener;
use Modules\Messagings\Listeners\SendParticipantRemovedNotificationListener;
use Modules\Messagings\Listeners\StoreMessageStatisticsListener;
use Modules\Messagings\Listeners\UpdateMessageForwardStatistic;
use Modules\Messagings\Listeners\UpdateMessageReplyStatistic;

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
