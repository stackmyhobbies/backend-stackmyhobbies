<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        User::create([
            "first_name" => "Jorge Andres",
            "last_name" => "Salgado Echeverria",
            "username" => "jorge019s",
            "email" => "jasen019@gmail.com",
            "password" => "123456"
        ]);
        $this->call([
            TagSeeder::class,
            ContentStatusSeeder::class,
            ContentTypeSeeder::class,
            // ContentItemSeeder::class
        ]);
    }
}
