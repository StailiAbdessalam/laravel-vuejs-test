<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Categories>
 */
class CategoriesFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $writeUsers = User::where('role','write')->pluck('id')->toArray();

        return [
            'name' => $this->faker->word,
            'image' => $this->faker->imageUrl(),
            'user_id' => $this->faker->randomElement($writeUsers),
        ];
    }
}
