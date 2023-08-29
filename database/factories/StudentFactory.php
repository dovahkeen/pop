<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Student>
 */
class StudentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'full_name' => $this->faker->name,
            'grade'     => $this->faker->unique()->numberBetween(0, 12),
            'username'  => $this->faker->unique()->userName,
            'password'  => $this->faker->password(6, 10), // password
        ];
    }
}
