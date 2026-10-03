<?php

namespace Modules\Core\database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Modules\Core\Entities\User;

class UserFactory extends Factory
{
    protected $model = User::class;

    public function definition(): array
    {
        return [
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),

            'first_name_ar' => null,
            'last_name_ar' => null,

            'gender' => fake()->randomElement([
                'male',
                'female',
            ]),

            'date_of_birth' => fake()->optional()->date(),

            'email' => fake()->unique()->safeEmail(),

            'password' => Hash::make('password'),

            'phone' => fake()->optional()->phoneNumber(),

            'avatar' => null,

            'user_type' => fake()->randomElement([
                'admin',
                'teacher',
                'student',
                'parent',
                'counselor',
            ]),

            'is_active' => true,

            'email_verified_at' => now(),

            'remember_token' => null,
        ];
    }

    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
