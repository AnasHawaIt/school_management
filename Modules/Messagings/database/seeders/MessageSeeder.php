<?php

namespace Modules\Messagings\database\seeders;

use Illuminate\Database\Seeder;
use Modules\Messagings\app\Entities\Conversation;
use Modules\Messagings\app\Entities\Message;

class MessageSeeder extends Seeder
{
    public function run(): void
    {
        $conversations = Conversation::query()
            ->with('participants')
            ->get();

        foreach ($conversations as $conversation) {

            $participants = $conversation->participants;

            if ($participants->isEmpty()) {
                continue;
            }

            /*
            |--------------------------------------------------------------------------
            | Number of messages
            |--------------------------------------------------------------------------
            */

            $messageCount = match ($conversation->type) {

                'private' => fake()->numberBetween(3, 8),

                'group' => fake()->numberBetween(8, 20),

                'broadcast' => fake()->numberBetween(5, 15),

                default => 5,
            };


            /*
            |--------------------------------------------------------------------------
            | Create Messages
            |--------------------------------------------------------------------------
            */

            for ($i = 0; $i < $messageCount; $i++) {

                $sender = $participants->random();

                $message = Message::factory()
                    ->make([
                        'sender_id' => $sender->id,
                        'conversation_id' => $conversation->id,
                    ]);

                $message->save();

                /*
                |--------------------------------------------------------------------------
                | Recipients
                |--------------------------------------------------------------------------
                */

                $recipients = $participants
                    ->where('id', '!=', $sender->id);

                foreach ($recipients as $recipient) {

                    $isRead = fake()->boolean(70);

                    $message->recipients()->create([
                        'recipient_id' => $recipient->id,
                        'is_read' => $isRead,
                        'read_at' => $isRead
                            ? fake()->dateTimeBetween(
                                $message->created_at,
                                'now'
                            )
                            : null,
                        'is_archived' => false,
                        'is_deleted' => false,
                    ]);
                }
            }

            /*
            |--------------------------------------------------------------------------
            | Update last message time
            |--------------------------------------------------------------------------
            */

            $lastMessage = $conversation
                ->messages()
                ->latest()
                ->first();

            if ($lastMessage) {

                $conversation->update([
                    'last_message_at' =>
                        $lastMessage->created_at,
                ]);
            }
        }
    }
}
