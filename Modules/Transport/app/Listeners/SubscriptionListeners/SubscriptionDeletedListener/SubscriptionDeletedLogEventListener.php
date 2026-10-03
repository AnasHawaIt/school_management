<?php

namespace Modules\Transport\Listeners\SubscriptionListeners\SubscriptionDeletedListener;


use Modules\Transport\Events\SubscriptionEvents\SubscriptionDeleted;

class SubscriptionDeletedLogEventListener
{
    public function handle(SubscriptionDeleted $event)
    {
        $subscription = $event->subscription;

        activity()
            ->causedBy(auth()->user())
            ->performedOn($subscription)
            ->withProperties([
                'Subscription_id' => $subscription->id,
            ])
            ->log('Subscription.Deleted');
    }
}
