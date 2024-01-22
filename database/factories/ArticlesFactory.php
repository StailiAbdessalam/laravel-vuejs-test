<?php

namespace Database\Factories;

use App\Models\Categories;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Articles>
 */
class ArticlesFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $writeUsers = User::where('role','write')->pluck('id')->toArray();
        $categoryIds = Categories::pluck('id')->toArray();
        return [
            'name' => $this->faker->sentence,
            'image' => $this->faker->imageUrl(),
            'description' => $this->faker->paragraph,
            'category_id' => $this->faker->randomElement($categoryIds),
            'user_id' => $this->faker->randomElement($writeUsers),
        ];
    }
}
