<?php

namespace Modules\Messagings\database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Messagings\Entities\Message;
use Modules\Messagings\Entities\MessageAttachment;

class MessageAttachmentFactory extends Factory
{
    protected $model = MessageAttachment::class;

    public function definition(): array
    {
        return [
            'message_id' => Message::factory(),

            'file_name' => fake()->word() . '.pdf',

            'file_path' => 'messages/test/' . fake()->uuid() . '.pdf',

            'mime_type' => 'application/pdf',

            'file_size' => fake()->numberBetween(
                10_000,
                5_000_000
            ),

            'duration' => null,
        ];
    }


    /**
     * Voice attachment.
     */
    public function voice(): static
    {
        return $this->state(fn () => [
            'file_name' => fake()->word() . '.mp3',

            'file_path' => 'messages/test/voice/'
                . fake()->uuid()
                . '.mp3',

            'mime_type' => 'audio/mpeg',

            'file_size' => fake()->numberBetween(
                100_000,
                10_000_000
            ),

            'duration' => fake()->numberBetween(
                5,
                300
            ),
        ]);
    }


    /**
     * Image attachment.
     */
    public function image(): static
    {
        return $this->state(fn () => [
            'file_name' => fake()->word() . '.jpg',

            'file_path' => 'messages/test/images/'
                . fake()->uuid()
                . '.jpg',

            'mime_type' => 'image/jpeg',

            'file_size' => fake()->numberBetween(
                20_000,
                8_000_000
            ),

            'duration' => null,
        ]);
    }
}
