<?php

namespace Modules\Transport\app\Listeners\SubscriptionListeners\SubscriptionCreatedListener;

use Modules\Transport\app\Events\SubscriptionEvents\SubscriptionCreated;

class SubscriptionCreatedLogEventListener
{
    public function handle(SubscriptionCreated $event)
    {
        $subscription = $event->subscription;

        activity()->causedBy(auth()->user())
            ->performedOn($subscription)
            ->withProperties([
                'Subscription_id' => $subscription->id,
            ])
            ->log('Subscription.created');
    }
}
