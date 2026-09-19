<?php


namespace Modules\Transport\app\Events\SubscriptionEvents;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Modules\Transport\app\Entities\Subscription;

class SubscriptionRestored
{
    use Dispatchable, SerializesModels;

    public Subscription $subscription;

    public function __construct(Subscription $subscription)
    {
        $this->subscription = $subscription;
    }
}
