<?php

namespace Modules\Messagings\database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Core\app\Entities\User;
use Modules\Messagings\app\Entities\Conversation;

class ConversationFactory extends Factory
{
    protected $model = Conversation::class;

    public function definition(): array
    {
        return [
            'type' => fake()->randomElement([
                'private',
                'group',
                'broadcast',
            ]),

            'title' => fake()->optional()->sentence(
                fake()->numberBetween(2, 5)
            ),

            'created_by' => User::factory(),

            'last_message_at' => fake()->optional()->dateTimeBetween(
                '-30 days',
                'now'
            ),
        ];
    }


    /**
     * Private conversation.
     */
    public function private(): static
    {
        return $this->state(fn () => [
            'type' => 'private',
            'title' => null,
        ]);
    }


    /**
     * Group conversation.
     */
    public function group(): static
    {
        return $this->state(fn () => [
            'type' => 'group',
            'title' => fake()->sentence(
                fake()->numberBetween(2, 5)
            ),
        ]);
    }


    /**
     * Broadcast conversation.
     */
    public function broadcast(): static
    {
        return $this->state(fn () => [
            'type' => 'broadcast',
            'title' => fake()->sentence(
                fake()->numberBetween(2, 5)
            ),
        ]);
    }
}
