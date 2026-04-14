<?php


namespace Modules\Transport\Events\SubscriptionEvents;

use Modules\Transport\Entities\Bus;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Modules\Transport\Entities\Subscription;

class SubscriptionRestored
{
    use Dispatchable, SerializesModels;

    public Subscription $subscription;

    public function __construct(Subscription $subscription)
    {
        $this->subscription = $subscription;
    }
}
