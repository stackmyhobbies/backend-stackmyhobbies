<?php

namespace Database\Seeders;

use App\Models\ContentItem;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Enums\SegmentType;

class ContentItemSeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            [
                'title' => 'One Piece',
                'description' => 'Aventura pirata de larga duración.',
                'type_id' => 1,
                'status_id' => 2,
                'image_url' => 'http://example.com/onepiece.jpg',
                'start_date' => '2023-02-01 00:00:00',
                'end_date' => null,
                'current_progress' => 1075,
                'total_progress' => 1100,
                'progress_unit' => 'episodios',
                'rating' => 10,
                'notes' => 'Aún en emisión.',
                'status' => true,
                'tags' => [3, 6, 9],
                'segment_type' => 'season',
                'segment_number' => 20,
            ],
            [
                'title' => 'Death Note',
                'description' => 'El poder de un cuaderno mortal.',
                'type_id' => 1,
                'status_id' => 3,
                'image_url' => 'http://example.com/deathnote.jpg',
                'start_date' => '2022-05-01 00:00:00',
                'end_date' => '2022-06-01 00:00:00',
                'current_progress' => 37,
                'total_progress' => 37,
                'progress_unit' => 'episodios',
                'rating' => 9,
                'notes' => 'Obra maestra psicológica.',
                'status' => true,
                'tags' => [2, 4, 6],
                'segment_type' => 'season',
                'segment_number' => 1,
            ],
            [
                'title' => 'Berserk',
                'description' => 'Oscuridad, sangre y espadas.',
                'type_id' => 2,
                'status_id' => 4,
                'image_url' => 'http://example.com/berserk.jpg',
                'start_date' => '2021-01-01 00:00:00',
                'end_date' => null,
                'current_progress' => 50,
                'total_progress' => 364,
                'progress_unit' => 'paginas',
                'rating' => 8,
                'notes' => 'Crudo y profundo.',
                'status' => true,
                'tags' => [1, 5, 10],
                'segment_type' => 'volume',
                'segment_number' => 8,
            ],
            [
                'title' => 'Jujutsu Kaisen',
                'description' => 'Exorcistas luchando contra maldiciones.',
                'type_id' => 1,
                'status_id' => 1,
                'image_url' => 'http://example.com/jujutsu.jpg',
                'start_date' => '2023-07-01 00:00:00',
                'end_date' => null,
                'current_progress' => 12,
                'total_progress' => 24,
                'progress_unit' => 'episodios',
                'rating' => 8,
                'notes' => '',
                'status' => true,
                'tags' => [3, 7, 11],
                'segment_type' => 'season',
                'segment_number' => 2,
            ],
            [
                'title' => 'Spirited Away',
                'description' => 'Una niña entra a un mundo mágico.',
                'type_id' => 5,
                'status_id' => 7,
                'image_url' => 'http://example.com/spirited.jpg',
                'start_date' => '2022-10-10 00:00:00',
                'end_date' => '2022-10-10 00:00:00',
                'current_progress' => 120,
                'total_progress' => 120,
                'progress_unit' => 'minutos',
                'rating' => 10,
                'notes' => 'Hermosa y emotiva.',
                'status' => true,
                'tags' => [4, 8, 12],
                'segment_type' => 'movie',
                'segment_number' => 1,
            ],
            [
                'title' => 'Tokyo Ghoul',
                'description' => 'Híbridos humanos y ghouls.',
                'type_id' => 1,
                'status_id' => 5,
                'image_url' => 'http://example.com/tokyoghoul.jpg',
                'start_date' => '2021-03-01 00:00:00',
                'end_date' => '2021-06-01 00:00:00',
                'current_progress' => 12,
                'total_progress' => 24,
                'progress_unit' => 'episodios',
                'rating' => 7,
                'notes' => 'Oscuro e intrigante.',
                'status' => true,
                'tags' => [2, 4, 10],
                'segment_type' => 'season',
                'segment_number' => 1,
            ],
            [
                'title' => 'Mob Psycho 100',
                'description' => 'Poder psíquico y adolescencia.',
                'type_id' => 1,
                'status_id' => 2,
                'image_url' => 'http://example.com/mob.jpg',
                'start_date' => '2022-09-01 00:00:00',
                'end_date' => null,
                'current_progress' => 8,
                'total_progress' => 12,
                'progress_unit' => 'episodios',
                'rating' => 8,
                'notes' => 'Sorprendente animación.',
                'status' => true,
                'tags' => [1, 3, 5],
                'segment_type' => 'season',
                'segment_number' => 3,
            ],
            [
                'title' => 'Vinland Saga',
                'description' => 'Vikingos, guerra y redención.',
                'type_id' => 2,
                'status_id' => 3,
                'image_url' => 'http://example.com/vinland.jpg',
                'start_date' => '2023-01-01 00:00:00',
                'end_date' => '2023-06-01 00:00:00',
                'current_progress' => 24,
                'total_progress' => 24,
                'progress_unit' => 'episodios',
                'rating' => 9,
                'notes' => 'Historia madura y realista.',
                'status' => true,
                'tags' => [6, 9, 12],
                'segment_type' => 'season',
                'segment_number' => 2,
            ],
        ];

        foreach ($items as $item) {
            $tags = $item['tags'];
            unset($item['tags']);

            $item['user_id'] = 1;

            // Crear el segment_label dinámicamente

            $item['segment_type'] = SegmentType::tryFrom($item['segment_type'])->value;

            $content = new ContentItem();
            $content->fill($item);
            $content->segment_type = $item['segment_type'];
            // asegúrate que sea enum
            $content->save();

            $content->tags()->sync($tags);
        }
    }
}
