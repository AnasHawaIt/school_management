<?php

namespace Modules\Messagings\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Messagings\Entities\MessageStatistic;
use Modules\Messagings\Events\MessageCreated;

class StoreMessageStatisticsListener implements ShouldQueue
{
    public function handle(MessageCreated $event): void
    {
        MessageStatistic::create([
            'message_id' => $event->message->id,
            'sender_id' => $event->senderId,
            'created_at' => now(),
        ]);
    }
}
