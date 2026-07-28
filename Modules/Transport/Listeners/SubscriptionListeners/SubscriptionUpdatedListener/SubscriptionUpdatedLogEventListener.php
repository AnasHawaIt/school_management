<?php

namespace Modules\Transport\Listeners\SubscriptionListeners\SubscriptionUpdatedListener;

use Modules\Transport\Events\SubscriptionEvents\SubscriptionUpdated;

class SubscriptionUpdatedLogEventListener
{
    public function handle(SubscriptionUpdated $event)
    {
        $subscription = $event->subscription;

        activity()
            ->causedBy($event->userId)
            ->performedOn($subscription)
            ->withProperties([
                'Subscription_id' => $subscription->id,
            ])
            ->log('Subscription.Updated');
    }
}
