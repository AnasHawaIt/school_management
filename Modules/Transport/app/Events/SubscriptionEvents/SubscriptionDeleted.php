<?php

namespace Modules\Transport\app\Events\SubscriptionEvents;

use Modules\Transport\app\Entities\Subscription;

class SubscriptionDeleted
{
    public function __construct(
        public Subscription $subscription,
        public ?int $userId = null
    ) {}
}


