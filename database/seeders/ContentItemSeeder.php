<?php

namespace Database\Seeders;

use App\Enums\ProgressUnit;
use App\Enums\SegmentType;
use App\Enums\SubSegmentType;
use App\Models\ContentItem;
use App\Models\ContentType;
use App\Models\ProgressStatus;
use App\Models\Tag;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ContentItemSeeder extends Seeder
{
    public function run(): void
    {
        $anime = ContentType::where('name', 'anime')->firstOrFail();
        $manga = ContentType::where('name', 'manga')->firstOrFail();
        $movie = ContentType::where('name', 'movie')->firstOrFail();

        $status = ProgressStatus::pluck('id', 'name');

        $tags = Tag::pluck('id', 'name');

        $items = [
            [
                'title' => 'One Piece',
                'description' => 'Aventura pirata de larga duración.',
                'content_type_id' => $anime->id,
                'progress_status_id' => $status['Watching'],
                'viewing_started_at' => '2023-02-01 00:00:00',
                'viewing_finished_at' => null,
                'current_progress' => 1075,
                'total_progress' => 1100,
                'progress_unit' => ProgressUnit::EPISODES->value,
                'rating' => 10,
                'notes' => 'Aún en emisión.',
                'is_active' => true,
                'tag_names' => ['shonen', 'adventure', 'action'],
                'segment_type' => SegmentType::SEASON->value,
                'segment_number' => 20,
            ],
            [
                'title' => 'Death Note',
                'description' => 'El poder de un cuaderno mortal.',
                'content_type_id' => $anime->id,
                'progress_status_id' => $status['Finished'],
                'viewing_started_at' => '2022-05-01 00:00:00',
                'viewing_finished_at' => '2022-06-01 00:00:00',
                'current_progress' => 37,
                'total_progress' => 37,
                'progress_unit' => ProgressUnit::EPISODES->value,
                'rating' => 9,
                'notes' => 'Obra maestra psicológica.',
                'is_active' => true,
                'tag_names' => ['psychological', 'thriller', 'action'],
                'segment_type' => SegmentType::SEASON->value,
                'segment_number' => 1,
            ],
            [
                'title' => 'Berserk',
                'description' => 'Oscuridad, sangre y espadas.',
                'content_type_id' => $manga->id,
                'progress_status_id' => $status['On Hold'],
                'viewing_started_at' => '2021-01-01 00:00:00',
                'viewing_finished_at' => null,
                'current_progress' => 50,
                'total_progress' => 364,
                'progress_unit' => ProgressUnit::PAGES->value,
                'rating' => 8,
                'notes' => 'Crudo y profundo.',
                'is_active' => true,
                'tag_names' => ['horror', 'action', 'drama'],
                'segment_type' => SegmentType::VOLUME->value,
                'segment_number' => 8,
                'segment_subtype' => SubSegmentType::CHAPTER->value,
                'segment_subnumber' => 5,
            ],
            [
                'title' => 'Jujutsu Kaisen',
                'description' => 'Exorcistas luchando contra maldiciones.',
                'content_type_id' => $anime->id,
                'progress_status_id' => $status['Airing'],
                'viewing_started_at' => '2023-07-01 00:00:00',
                'viewing_finished_at' => null,
                'current_progress' => 12,
                'total_progress' => 24,
                'progress_unit' => ProgressUnit::EPISODES->value,
                'rating' => 8,
                'notes' => '',
                'is_active' => true,
                'tag_names' => ['shonen', 'action', 'supernatural'],
                'segment_type' => SegmentType::SEASON->value,
                'segment_number' => 2,
            ],
            [
                'title' => 'Spirited Away',
                'description' => 'Una niña entra a un mundo mágico.',
                'content_type_id' => $movie->id,
                'progress_status_id' => $status['Finished'],
                'viewing_started_at' => '2022-10-10 00:00:00',
                'viewing_finished_at' => '2022-10-10 00:00:00',
                'current_progress' => 120,
                'total_progress' => 120,
                'progress_unit' => ProgressUnit::MINUTES->value,
                'rating' => 10,
                'notes' => 'Hermosa y emotiva.',
                'is_active' => true,
                'tag_names' => ['fantasy', 'adventure', 'drama'],
                'segment_type' => SegmentType::MOVIE->value,
                'segment_number' => 1,
            ],
            [
                'title' => 'Tokyo Ghoul',
                'description' => 'Híbridos humanos y ghouls.',
                'content_type_id' => $anime->id,
                'progress_status_id' => $status['Dropped'],
                'viewing_started_at' => '2021-03-01 00:00:00',
                'viewing_finished_at' => '2021-06-01 00:00:00',
                'current_progress' => 12,
                'total_progress' => 24,
                'progress_unit' => ProgressUnit::EPISODES->value,
                'rating' => 7,
                'notes' => 'Oscuro e intrigante.',
                'is_active' => true,
                'tag_names' => ['horror', 'thriller', 'action'],
                'segment_type' => SegmentType::SEASON->value,
                'segment_number' => 1,
            ],
            [
                'title' => 'Mob Psycho 100',
                'description' => 'Poder psíquico y adolescencia.',
                'content_type_id' => $anime->id,
                'progress_status_id' => $status['Watching'],
                'viewing_started_at' => '2022-09-01 00:00:00',
                'viewing_finished_at' => null,
                'current_progress' => 8,
                'total_progress' => 12,
                'progress_unit' => ProgressUnit::EPISODES->value,
                'rating' => 8,
                'notes' => 'Sorprendente animación.',
                'is_active' => true,
                'tag_names' => ['action', 'comedy', 'supernatural'],
                'segment_type' => SegmentType::SEASON->value,
                'segment_number' => 3,
            ],
            [
                'title' => 'Vinland Saga',
                'description' => 'Vikingos, guerra y redención.',
                'content_type_id' => $anime->id,
                'progress_status_id' => $status['Finished'],
                'viewing_started_at' => '2023-01-01 00:00:00',
                'viewing_finished_at' => '2023-06-01 00:00:00',
                'current_progress' => 24,
                'total_progress' => 24,
                'progress_unit' => ProgressUnit::EPISODES->value,
                'rating' => 9,
                'notes' => 'Historia madura y realista.',
                'is_active' => true,
                'tag_names' => ['adventure', 'drama', 'action'],
                'segment_type' => SegmentType::SEASON->value,
                'segment_number' => 2,
            ],
        ];

        foreach ($items as $item) {
            $tagNames = $item['tag_names'];
            unset($item['tag_names']);

            $item['user_id'] = 1;

            $slug = Str::slug($item['title'].' '.$item['segment_type'].' '.$item['segment_number']);
            $item['slug'] = $slug;

            $content = ContentItem::updateOrCreate(
                [
                    'user_id' => $item['user_id'],
                    'title' => $item['title'],
                    'segment_type' => $item['segment_type'],
                    'segment_number' => $item['segment_number'],
                ],
                $item
            );

            $tagIds = [];
            foreach ($tagNames as $tagName) {
                $tag = Tag::where('name', $tagName)->firstOrFail();
                $tagIds[] = $tag->id;
            }
            $content->tags()->sync($tagIds);
        }
    }
}
