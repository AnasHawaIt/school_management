<?php

namespace Modules\Transport\Events\SubscriptionEvents;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Modules\Transport\Entities\Subscription;


class SubscriptionCreated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public Subscription $subscription,
        public ?int $userId = null,
        public ?string $socketId = null
    ) {}
}

