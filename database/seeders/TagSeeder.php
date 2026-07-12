<?php

namespace Database\Seeders;

use App\Models\Tag;
use Illuminate\Database\Seeder;

class TagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tags = [
            // Demographics
            'Shonen',
            'Seinen',
            'Shojo',
            'Josei',
            'Kids',

            // Main Genres
            'Action',
            'Adventure',
            'Comedy',
            'Drama',
            'Fantasy',
            'Horror',
            'Mystery',
            'Romance',
            'Sci-Fi',
            'Supernatural',
            'Thriller',
            'Psychological',

            // Subgenres & Themes
            'Isekai',
            'Mecha',
            'Slice of Life',
            'Iyashikei',
            'Sports',
            'Music',
            'Cyberpunk',
            'Steampunk',
            'Post-Apocalyptic',
            'Super Power',
            'Martial Arts',
            'Historical',
            'Military',
            'Space',
            'Ecchi',
            'Harem',
            'Reverse Harem',
            'School',
            'Vampire',
            'Zombies',
            'Gore',
            'Parody',
            'Survival',
        ];

        foreach ($tags as $name) {
            Tag::updateOrCreate(['name' => $name], [
                'status' => true,
            ]);
        }
    }
}
