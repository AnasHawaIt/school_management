<?php

namespace Modules\Transport\Listeners\SubscriptionListeners\SubscriptionCreatedListener;

use Modules\Transport\Events\SubscriptionEvents\SubscriptionCreated;

class SubscriptionCreatedLogEventListener
{
    public function handle(SubscriptionCreated $event)
    {
        $subscription = $event->subscription;

        activity()
            ->causedBy($event->userId)
            ->performedOn($subscription)
            ->withProperties([
                'Subscription_id' => $subscription->id,
            ])
            ->log('Subscription.created');
    }
}
