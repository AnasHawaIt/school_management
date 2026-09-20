<?php

namespace Modules\Transport\app\Listeners\SubscriptionListeners\SubscriptionUpdatedListener;

use Modules\Transport\app\Events\SubscriptionEvents\SubscriptionUpdated;

class SubscriptionUpdatedLogEventListener
{
    public function handle(SubscriptionUpdated $event)
    {
        $subscription = $event->subscription;

        activity()->causedBy(auth()->user())
            ->performedOn($subscription)
            ->withProperties([
                'Subscription_id' => $subscription->id,
            ])
            ->log('Subscription.Updated');
    }
}
