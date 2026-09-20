<?php

namespace Modules\Transport\app\Listeners\SubscriptionListeners\SubscriptionDeletedListener;

use Modules\Notifications\app\Services\NotificationService;
use Modules\Transport\app\Events\SubscriptionEvents\SubscriptionDeleted;

class SubscriptionDeletedNotificationDatabaseListener
{
    public function __construct(
        protected NotificationService $notificationService
    )
    {
    }

    public function handle(SubscriptionDeleted $event): void
    {
        $subscription = $event->subscription;

        $this->notificationService->sendToAll(
            title: 'Deleted subscription',
            body: 'Deleted subscription',
            type: 'Transport',
            data: [
                'entity' => 'subscription',
                'action' => 'DELETE',
                'subscription_id' => $subscription->id,
            ]
        );
    }
}
