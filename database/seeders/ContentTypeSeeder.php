<?php

namespace Database\Seeders;

use App\Models\ContentType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ContentTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $types = [
            'Manga',
            'Novela gráfica',
            'Manhwa',
            'Novela',
            'Película',
            'Serie',
            'Documental',
            'Anime'
        ];

        foreach ($types as $name) {
            ContentType::updateOrCreate(['name' => $name], [
                'status' => true,
            ]);
        }
    }
}