<?php

namespace Modules\Messagings\Listeners;

use Modules\Messagings\Entities\MessageStatistic;
use Modules\Messagings\Events\Message\MessageReplied;

class UpdateMessageReplyStatistic
{
    public function handle(MessageReplied $event): void
    {
        $statistic = MessageStatistic::firstOrCreate(
            [
                'message_id' => $event->originalMessage->id,
            ],
            [
                'sender_id' => $event->originalMessage->sender_id,
                'read_count' => 0,
                'reply_count' => 0,
                'forward_count' => 0,
            ]
        );

        $statistic->increment('reply_count');
    }
}
