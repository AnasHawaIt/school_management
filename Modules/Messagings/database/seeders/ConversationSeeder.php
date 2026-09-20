<?php

namespace Modules\Messagings\database\seeders;

use Illuminate\Database\Seeder;
use Modules\Core\app\Entities\User;
use Modules\Messagings\app\Entities\Conversation;

class ConversationSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::query()
            ->where('is_active', true)
            ->get();

        if ($users->count() < 2) {
            $this->command->warn(
                'Not enough users to seed conversations.'
            );

            return;
        }

        /*
        |--------------------------------------------------------------------------
        | Private Conversations
        |--------------------------------------------------------------------------
        */

        Conversation::factory()
            ->count(5)
            ->private()
            ->create()
            ->each(function (Conversation $conversation) use ($users) {

                $owner = $users->random();

                $conversation->update([
                    'created_by' => $owner->id,
                ]);

                /*
                |--------------------------------------------------------------------------
                | Owner
                |--------------------------------------------------------------------------
                */

                $conversation->participants()->attach(
                    $owner->id,
                    [
                        'conversation_role' => 'owner',
                        'joined_at' => now(),
                    ]
                );

                /*
                |--------------------------------------------------------------------------
                | One Member
                |--------------------------------------------------------------------------
                */

                $member = $users
                    ->where('id', '!=', $owner->id)
                    ->random();

                $conversation->participants()->attach(
                    $member->id,
                    [
                        'conversation_role' => 'member',
                        'joined_at' => now(),
                    ]
                );
            });


        /*
        |--------------------------------------------------------------------------
        | Group Conversations
        |--------------------------------------------------------------------------
        */

        Conversation::factory()
            ->count(5)
            ->group()
            ->create()
            ->each(function (Conversation $conversation) use ($users) {

                $owner = $users->random();

                $conversation->update([
                    'created_by' => $owner->id,
                ]);

                /*
                |--------------------------------------------------------------------------
                | Owner
                |--------------------------------------------------------------------------
                */

                $conversation->participants()->attach(
                    $owner->id,
                    [
                        'conversation_role' => 'owner',
                        'joined_at' => now(),
                    ]
                );

                /*
                |--------------------------------------------------------------------------
                | Admin
                |--------------------------------------------------------------------------
                */

                $admin = $users
                    ->where('id', '!=', $owner->id)
                    ->random();

                $conversation->participants()->attach(
                    $admin->id,
                    [
                        'conversation_role' => 'admin',
                        'joined_at' => now(),
                    ]
                );

                /*
                |--------------------------------------------------------------------------
                | Members
                |--------------------------------------------------------------------------
                */

                $members = $users
                    ->whereNotIn('id', [
                        $owner->id,
                        $admin->id,
                    ])
                    ->shuffle()
                    ->take(
                        min(
                            3,
                            max(0, $users->count() - 2)
                        )
                    );

                foreach ($members as $member) {

                    $conversation->participants()->attach(
                        $member->id,
                        [
                            'conversation_role' => 'member',
                            'joined_at' => now(),
                        ]
                    );
                }
            });


        /*
        |--------------------------------------------------------------------------
        | Broadcast Conversations
        |--------------------------------------------------------------------------
        */

        Conversation::factory()
            ->count(2)
            ->broadcast()
            ->create()
            ->each(function (Conversation $conversation) use ($users) {

                $owner = $users->random();

                $conversation->update([
                    'created_by' => $owner->id,
                ]);

                $conversation->participants()->attach(
                    $owner->id,
                    [
                        'conversation_role' => 'owner',
                        'joined_at' => now(),
                    ]
                );

                $subscribers = $users
                    ->where('id', '!=', $owner->id)
                    ->shuffle()
                    ->take(
                        min(5, $users->count() - 1)
                    );

                foreach ($subscribers as $subscriber) {

                    $conversation->participants()->attach(
                        $subscriber->id,
                        [
                            'conversation_role' => 'member',
                            'joined_at' => now(),
                        ]
                    );
                }
            });
    }
}
