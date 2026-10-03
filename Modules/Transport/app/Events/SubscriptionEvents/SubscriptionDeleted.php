<?php

namespace Modules\Transport\Events\SubscriptionEvents;

use Modules\Transport\Entities\Subscription;

class SubscriptionDeleted
{
    public function __construct(
        public Subscription $subscription,
        public ?int $userId = null
    ) {}
}


