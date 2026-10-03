<?php

namespace Modules\Messagings\database\seeders;

use Illuminate\Database\Seeder;
use Modules\Messagings\Entities\Message;
use Modules\Messagings\Entities\MessageAttachment;

class MessageAttachmentSeeder extends Seeder
{
    public function run(): void
    {
        Message::query()
            ->get()
            ->each(function (Message $message) {

                /*
                |--------------------------------------------------------------------------
                | 30% chance of having attachments
                |--------------------------------------------------------------------------
                */

                if (!fake()->boolean(30)) {
                    return;
                }

                $count = fake()->numberBetween(1, 3);

                for ($i = 0; $i < $count; $i++) {

                    MessageAttachment::factory()
                        ->create([
                            'message_id' => $message->id,
                        ]);
                }
            });
    }
}
