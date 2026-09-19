<?php

namespace Modules\Messagings\app\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Messagings\app\Entities\MessageStatistic;
use Modules\Messagings\app\Events\Message\MessageCreated;

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
