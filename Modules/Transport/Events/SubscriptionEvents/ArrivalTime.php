<?php

    namespace Modules\Transport\Events\SubscriptionEvents;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Modules\Announcement\Entities\Announcement;
use Modules\Transport\Entities\Subscription;

class ArrivalTime
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public Subscription $subscription,
        // public array $phones,
        public $users
    ) {}
}

