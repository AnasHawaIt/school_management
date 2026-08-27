<?php

namespace Modules\Messagings\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Modules\Announcement\Events\AnnouncementUpdated;
use Modules\Library\Events\MemberEvents\MemberDeleted;
use Modules\Library\Events\MemberEvents\MemberRestored;
use Modules\Messagings\Events\AttachmentDeleted;
use Modules\Messagings\Events\MessageCreated;
use Modules\Messagings\Events\MessageFailed;
use Modules\Messagings\Events\MessageForwarded;
use Modules\Messagings\Events\MessageRead;
use Modules\Messagings\Events\MessageReplied;
use Modules\Messagings\Events\MessageSent;
use Modules\Messagings\Listeners\LogAttachmentUploadedEventListener;
use Modules\Messagings\Listeners\LogMessageCreatedEventListener;
use Modules\Messagings\Listeners\LogMessageDeletedEventListener;
use Modules\Messagings\Listeners\LogMessageFailedEventListener;
use Modules\Messagings\Listeners\LogMessageForwardedEventListener;
use Modules\Messagings\Listeners\LogMessageReadEventListener;
use Modules\Messagings\Listeners\LogMessageRepliedEventListener;
use Modules\Messagings\Listeners\LogMessageRestoredEventListener;
use Modules\Messagings\Listeners\SendEmailListener;
use Modules\Messagings\Listeners\SendMessageNotificationListener;
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
        MessageSent::class=>[
            SendMessageNotificationListener::class,
            SendEmailListener::class,
        ],

        MessageCreated::class => [
            LogMessageCreatedEventListener::class,
            StoreMessageStatisticsListener::class,
        ],

        MemberDeleted::class=>[
            LogMessageDeletedEventListener::class,
        ],

        MemberRestored::class=>[
            LogMessageRestoredEventListener::class,
        ],

        MessageForwarded::class=>[
            LogMessageForwardedEventListener::class,
            UpdateMessageForwardStatistic::class
        ],

        MessageFailed::class=>[
            LogMessageFailedEventListener::class,
        ],

        MessageRead::class=>[
            LogMessageReadEventListener::class,
        ],

        MessageReplied::class=>[
            LogMessageRepliedEventListener::class,
            UpdateMessageReplyStatistic::class

        ],

        AttachmentDeleted::class=>[
            LogMessageDeletedEventListener::class,
        ],

        AnnouncementUpdated::class=>[
            LogAttachmentUploadedEventListener::class,
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
