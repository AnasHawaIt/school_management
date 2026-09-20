<?php

namespace Modules\Messagings\database\seeders;

use Illuminate\Database\Seeder;
use Modules\Messagings\app\Entities\Message;
use Modules\Messagings\app\Entities\MessageStatistic;

class MessageStatisticsSeeder extends Seeder
{
    public function run(): void
    {
        Message::query()
            ->with('recipients')
            ->chunkById(100, function ($messages) {

                foreach ($messages as $message) {

                    $readCount = $message
                        ->recipients
                        ->where('is_read', true)
                        ->count();

                    $replyCount = 0;

                    $forwardCount = 0;

                    MessageStatistic::updateOrCreate(
                        [
                            'message_id' => $message->id,
                        ],
                        [
                            'sender_id' => $message->sender_id,

                            'read_count' => $readCount,

                            'reply_count' => $replyCount,

                            'forward_count' => $forwardCount,

                            'first_read_at' => null,

                            'last_read_at' => null,
                        ]
                    );
                }
            });
    }
}
