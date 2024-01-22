<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Articles;
use App\Models\Categories;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create 10 usphp ers with the 'user' role
        User::factory()->count(10)->create();

        // Create 1 user with the 'admin' role
        User::factory()->admin()->create();

        Categories::factory(10)->create();
        Articles::factory()->count(10)->create();

//        User::factory()->create([
//            'name' => 'Test User',
//            'email' => 'test@example.com',
//        ]);
    }
}
