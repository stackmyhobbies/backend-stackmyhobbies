<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\ContentType\ContentTypeLiteResource;
use App\Http\Resources\ContentStatus\ContentStatusLiteResource;




class ContentItemResource extends JsonResource
{
    public function toArray(Request $request): array
    {

        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'title' => $this->title,
            'slug' => $this->slug,
            'description' => $this->description,
            'type_id' => $this->type_id,

            'status_id' => $this->status_id,
            'segment_type' => $this->segment_type,
            'segment_number' => $this->segment_number,
            'segment_label' => $this->segment_label,

            'image_url' => $this->image_url,
            'start_date' => $this->start_date?->toDateTimeString(),
            'end_date' => $this->end_date?->toDateTimeString(),
            'current_progress' => $this->current_progress,
            'total_progress' => $this->total_progress,
            'progress_percent' => $this->progress_percentage,
            'progress_unit' => $this->progress_unit->value ?? null,
            'notes' => $this->notes,
            'rating' => $this->rating,
            'status' => $this->status,

            'tags' => $this->whenLoaded(
                'tags',
                fn() =>
                $this->tags->map(fn($tag) => [
                    'id' => $tag->id,
                    'name' => $tag->name,
                ])
            ),

            'type' => new ContentTypeLiteResource($this->whenLoaded('contentType')),
            'status_detail' => new ContentStatusLiteResource($this->whenLoaded('contentStatus')),
        ];
    }
}
