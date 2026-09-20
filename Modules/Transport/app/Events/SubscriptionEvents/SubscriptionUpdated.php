<?php

namespace Modules\Transport\app\Events\SubscriptionEvents;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Modules\Transport\app\Entities\Subscription;

class SubscriptionUpdated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public Subscription $subscription,
        public ?int $userId = null,
        public array $changes = []
    ) {}
}
