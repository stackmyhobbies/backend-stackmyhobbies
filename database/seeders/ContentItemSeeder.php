<?php

namespace Database\Seeders;

use App\Enums\ProgressUnit;
use App\Models\ContentItem;
use App\Models\ContentType;
use App\Models\ProgressStatus;
use App\Models\Tag;
use Illuminate\Database\Seeder;
use App\Enums\SegmentType;
use App\Enums\SubSegmentType;
use Illuminate\Support\Str;

class ContentItemSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Cargamos los tipos de contenido (mapeo por nombre para evitar IDs mágicos)
        $anime = ContentType::where('name', 'anime')->firstOrFail();
        $manga = ContentType::where('name', 'manga')->firstOrFail();
        $movie = ContentType::where('name', 'película')->firstOrFail();

        // 2. Cargamos los estados (mapeo por nombre para evitar IDs mágicos)
        $status = ProgressStatus::pluck('id', 'name'); // Esto crea un array tipo: ['Viendo' => 3, 'Finalizado' => 2...]

        // 3. Cargamos los tags (mapeo por nombre)
        $tags = Tag::pluck('id', 'name');

        $items = [
            [
                'title' => 'One Piece',
                'description' => 'Aventura pirata de larga duración.',
                'content_type_id' => $anime->id,
                'progress_status_id' => $status['Viendo'],
                'image_url' => 'http://example.com/onepiece.jpg',
                'start_date' => '2023-02-01 00:00:00',
                'end_date' => null,
                'current_progress' => 1075,
                'total_progress' => 1100,
                'progress_unit' => ProgressUnit::EPISODES->value,
                'rating' => 10,
                'notes' => 'Aún en emisión.',
                'is_active' => true,
                'tag_names' => ['shonen', 'aventura', 'acción'],
                'segment_type' => SegmentType::SEASON->value,
                'segment_number' => 20,
            ],
            [
                'title' => 'Death Note',
                'description' => 'El poder de un cuaderno mortal.',
                'content_type_id' => $anime->id,
                'progress_status_id' => $status['Finalizado'],
                'image_url' => 'http://example.com/deathnote.jpg',
                'start_date' => '2022-05-01 00:00:00',
                'end_date' => '2022-06-01 00:00:00',
                'current_progress' => 37,
                'total_progress' => 37,
                'progress_unit' => ProgressUnit::EPISODES->value,
                'rating' => 9,
                'notes' => 'Obra maestra psicológica.',
                'is_active' => true,
                'tag_names' => ['psicológico', 'suspenso', 'acción'],
                'segment_type' => SegmentType::SEASON->value,
                'segment_number' => 1,
            ],
            [
                'title' => 'Berserk',
                'description' => 'Oscuridad, sangre y espadas.',
                'content_type_id' => $manga->id,
                'progress_status_id' => $status['Pausado'],
                'image_url' => 'http://example.com/berserk.jpg',
                'start_date' => '2021-01-01 00:00:00',
                'end_date' => null,
                'current_progress' => 50,
                'total_progress' => 364,
                'progress_unit' => ProgressUnit::PAGES->value,
                'rating' => 8,
                'notes' => 'Crudo y profundo.',
                'is_active' => true,
                'tag_names' => ['terror', 'acción', 'drama'],
                'segment_type' => SegmentType::VOLUME->value,
                'segment_number' => 8,
                'segment_subtype' => SubSegmentType::CHAPTER->value,
                'segment_subnumber' => 5
            ],
            [
                'title' => 'Jujutsu Kaisen',
                'description' => 'Exorcistas luchando contra maldiciones.',
                'content_type_id' => $anime->id,
                'progress_status_id' => $status['En emisión'],
                'image_url' => 'http://example.com/jujutsu.jpg',
                'start_date' => '2023-07-01 00:00:00',
                'end_date' => null,
                'current_progress' => 12,
                'total_progress' => 24,
                'progress_unit' => ProgressUnit::EPISODES->value,
                'rating' => 8,
                'notes' => '',
                'is_active' => true,
                'tag_names' => ['shonen', 'acción', 'sobrenatural'],
                'segment_type' => SegmentType::SEASON->value,
                'segment_number' => 2,
            ],
            [
                'title' => 'Spirited Away',
                'description' => 'Una niña entra a un mundo mágico.',
                'content_type_id' => $movie->id,
                'progress_status_id' => $status['Finalizado'],
                'image_url' => 'http://example.com/spirited.jpg',
                'start_date' => '2022-10-10 00:00:00',
                'end_date' => '2022-10-10 00:00:00',
                'current_progress' => 120,
                'total_progress' => 120,
                'progress_unit' => ProgressUnit::MINUTES->value,
                'rating' => 10,
                'notes' => 'Hermosa y emotiva.',
                'is_active' => true,
                'tag_names' => ['fantasia', 'aventura', 'drama'],
                'segment_type' => SegmentType::MOVIE->value,
                'segment_number' => 1,
            ],
            [
                'title' => 'Tokyo Ghoul',
                'description' => 'Híbridos humanos y ghouls.',
                'content_type_id' => $anime->id,
                'progress_status_id' => $status['Abandonado'],
                'image_url' => 'http://example.com/tokyoghoul.jpg',
                'start_date' => '2021-03-01 00:00:00',
                'end_date' => '2021-06-01 00:00:00',
                'current_progress' => 12,
                'total_progress' => 24,
                'progress_unit' => ProgressUnit::EPISODES->value,
                'rating' => 7,
                'notes' => 'Oscuro e intrigante.',
                'is_active' => true,
                'tag_names' => ['terror', 'suspenso', 'acción'],
                'segment_type' => SegmentType::SEASON->value,
                'segment_number' => 1,
            ],
            [
                'title' => 'Mob Psycho 100',
                'description' => 'Poder psíquico y adolescencia.',
                'content_type_id' => $anime->id,
                'progress_status_id' => $status['Viendo'],
                'image_url' => 'http://example.com/mob.jpg',
                'start_date' => '2022-09-01 00:00:00',
                'end_date' => null,
                'current_progress' => 8,
                'total_progress' => 12,
                'progress_unit' =>  ProgressUnit::EPISODES->value,
                'rating' => 8,
                'notes' => 'Sorprendente animación.',
                'is_active' => true,
                'tag_names' => ['acción', 'comedia', 'sobrenatural'],
                'segment_type' => SegmentType::SEASON->value,
                'segment_number' => 3,
            ],
            [
                'title' => 'Vinland Saga',
                'description' => 'Vikingos, guerra y redención.',
                'content_type_id' => $anime->id,
                'progress_status_id' => $status['Finalizado'],
                'image_url' => 'http://example.com/vinland.jpg',
                'start_date' => '2023-01-01 00:00:00',
                'end_date' => '2023-06-01 00:00:00',
                'current_progress' => 24,
                'total_progress' => 24,
                'progress_unit' =>  ProgressUnit::EPISODES->value,
                'rating' => 9,
                'notes' => 'Historia madura y realista.',
                'is_active' => true,
                'tag_names' => ['aventura', 'drama', 'acción'],
                'segment_type' => SegmentType::SEASON->value,
                'segment_number' => 2,
            ],
        ];

        foreach ($items as $item) {
            $tagNames = $item['tag_names'];
            unset($item['tag_names']);

            $item['user_id'] = 1;

            // Generar el slug manualmente
            $slug = Str::slug($item['title'] . ' ' . $item['segment_type'] . ' ' . $item['segment_number']);
            $item['slug'] = $slug;

            // Usar updateOrCreate para evitar duplicados
            $content = ContentItem::updateOrCreate(
                [
                    'user_id' => $item['user_id'],
                    'title' => $item['title'],
                    'segment_type' => $item['segment_type'],
                    'segment_number' => $item['segment_number'],
                ],
                $item
            );

            // Asociar los tags por nombre
            $tagIds = [];
            foreach ($tagNames as $tagName) {
                $tag = Tag::where('name', $tagName)->firstOrFail();
                $tagIds[] = $tag->id;
            }
            $content->tags()->sync($tagIds);
        }
    }
}
