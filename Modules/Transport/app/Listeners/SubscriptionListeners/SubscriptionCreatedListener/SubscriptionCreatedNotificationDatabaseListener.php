<?php

namespace Modules\Transport\app\Listeners\SubscriptionListeners\SubscriptionCreatedListener;

use Modules\Notifications\app\Services\NotificationService;
use Modules\Transport\app\Events\SubscriptionEvents\SubscriptionCreated;

class SubscriptionCreatedNotificationDatabaseListener
{
    public function __construct(
        protected NotificationService $notificationService
    )
    {
    }

    public function handle(SubscriptionCreated $event): void
    {
        $subscription = $event->subscription;

        $this->notificationService->sendToAll(
            title: 'new subscription',
            body: 'new subscription has been created. .',
            type: 'Transport',
            data: [
                'entity' => 'subscription',
                'action' => 'Create',
                'subscription_id' => $subscription->id,
            ]
        );
    }
}
