<?php

namespace Modules\Messagings\database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Core\app\Entities\User;
use Modules\Messagings\Entities\Conversation;
use Modules\Messagings\Entities\Message;

class MessageFactory extends Factory
{
    protected $model = Message::class;

    public function definition(): array
    {
        return [
            'sender_id' => User::factory(),

            'conversation_id' => Conversation::factory(),

            'subject' => fake()->optional()->sentence(
                fake()->numberBetween(3, 6)
            ),

            'body' => fake()->paragraph(
                fake()->numberBetween(1, 4)
            ),

            'type' => 'text',

            'priority' => fake()->randomElement([
                'normal',
                'important',
                'urgent',
            ]),
        ];
    }


    /**
     * Text message.
     */
    public function text(): static
    {
        return $this->state(fn () => [
            'type' => 'text',
        ]);
    }


    /**
     * Voice message.
     */
    public function voice(): static
    {
        return $this->state(fn () => [
            'type' => 'voice',
            'body' => '',
        ]);
    }


    /**
     * Normal priority.
     */
    public function normal(): static
    {
        return $this->state(fn () => [
            'priority' => 'normal',
        ]);
    }


    /**
     * Important priority.
     */
    public function important(): static
    {
        return $this->state(fn () => [
            'priority' => 'important',
        ]);
    }


    /**
     * Urgent priority.
     */
    public function urgent(): static
    {
        return $this->state(fn () => [
            'priority' => 'urgent',
        ]);
    }
}

