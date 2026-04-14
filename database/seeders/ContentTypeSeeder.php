<?php

namespace Database\Seeders;

use App\Models\ContentType;
use App\Enums\ProgressUnit;
use App\Enums\SegmentType;
use App\Enums\SubSegmentType;
use Illuminate\Database\Seeder;

class ContentTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $types = [
            [
                'name' => 'manga',
                'status' => true,
                'allowed_units' => [
                    ProgressUnit::PAGES->value,
                    ProgressUnit::CHAPTERS->value
                ],
                'allowed_segment_types' => [
                    SegmentType::VOLUME->value
                ],
                'allowed_subsegment_types' => [
                    SubSegmentType::CHAPTER->value,
                    SubSegmentType::PAGE->value
                ],
            ],
            [
                'name' => 'manhwa',
                'status' => true,
                'allowed_units' => [
                    ProgressUnit::PAGES->value,
                    ProgressUnit::CHAPTERS->value
                ],
                'allowed_segment_types' => [
                    SegmentType::VOLUME->value
                ],
                'allowed_subsegment_types' => [
                    SubSegmentType::CHAPTER->value,
                    SubSegmentType::PAGE->value
                ],
            ],
            [
                'name' => 'novela gráfica',
                'status' => true,
                'allowed_units' => [
                    ProgressUnit::PAGES->value,
                    ProgressUnit::CHAPTERS->value
                ],
                'allowed_segment_types' => [
                    SegmentType::VOLUME->value,
                    SegmentType::EDITION->value
                ],
                'allowed_subsegment_types' => [
                    SubSegmentType::CHAPTER->value
                ],
            ],
            [
                'name' => 'novela',
                'status' => true,
                'allowed_units' => [
                    ProgressUnit::PAGES->value,
                    ProgressUnit::CHAPTERS->value
                ],
                'allowed_segment_types' => [
                    SegmentType::VOLUME->value,
                    SegmentType::PART->value
                ],
                'allowed_subsegment_types' => [
                    SubSegmentType::CHAPTER->value
                ],
            ],
            [
                'name' => 'anime',
                'status' => true,
                'allowed_units' => [
                    ProgressUnit::EPISODES->value
                ],
                'allowed_segment_types' => [
                    SegmentType::SEASON->value,
                    SegmentType::PART->value
                ],
                'allowed_subsegment_types' => [
                    SubSegmentType::EPISODE->value,
                    SubSegmentType::OVA->value,
                    SubSegmentType::SPECIAL->value
                ],
            ],
            [
                'name' => 'serie',
                'status' => true,
                'allowed_units' => [
                    ProgressUnit::EPISODES->value
                ],
                'allowed_segment_types' => [
                    SegmentType::SEASON->value,
                    SegmentType::PART->value
                ],
                'allowed_subsegment_types' => [
                    SubSegmentType::EPISODE->value
                ],
            ],
            [
                'name' => 'película',
                'status' => true,
                'allowed_units' => [
                    ProgressUnit::MINUTES->value
                ],
                'allowed_segment_types' => [
                    SegmentType::MOVIE->value,
                    SegmentType::PART->value,
                    SegmentType::SINGLE->value
                ],
                'allowed_subsegment_types' => [], // Atómico, no tiene subsegmentos estándar
            ],
            [
                'name' => 'documental',
                'status' => true,
                'allowed_units' => [
                    ProgressUnit::MINUTES->value,
                    ProgressUnit::EPISODES->value
                ],
                'allowed_segment_types' => [
                    SegmentType::MOVIE->value,
                    SegmentType::PART->value,
                    SegmentType::SINGLE->value
                ],
                'allowed_subsegment_types' => [
                    SubSegmentType::EPISODE->value,
                    SubSegmentType::PART->value
                ],
            ],
        ];

        foreach ($types as $type) {
            ContentType::updateOrCreate(
                ['name' => $type['name']], // Único por nombre
                [
                    'status' => $type['status'],
                    'allowed_units' => $type['allowed_units'],
                    'allowed_segment_types' => $type['allowed_segment_types'],
                    'allowed_subsegment_types' => $type['allowed_subsegment_types'],
                ]
            );
        }
    }
}
