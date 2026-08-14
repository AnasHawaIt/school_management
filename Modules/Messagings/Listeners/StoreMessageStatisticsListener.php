<?php

namespace Modules\Messagings\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Messagings\Entities\MessageStatistic;
use Modules\Messagings\Events\MessageCreated;

class StoreMessageStatisticsListener implements ShouldQueue
{
    public function handle(MessageCreated $event): void
    {
        $message = $event->message;

        MessageStatistic::create([
            'message_id' => $message->id,
            'sender_id' => $message->sender_id,
        ]);
    }
}
