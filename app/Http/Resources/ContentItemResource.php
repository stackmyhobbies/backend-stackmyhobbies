<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\ContentType\ContentTypeLiteResource;
use App\Http\Resources\ProgressStatus\ProgressStatusLiteResource;




class ContentItemResource extends JsonResource
{
    public function toArray(Request $request): array
    {

        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'title' => $this->title,
            'slug' => $this->slug,
            'description' => $this->when($request->routeIs('content-items.show'), $this->description),
            'content_type_id' => $this->content_type_id,

            'progress_status_id' => $this->progress_status_id,
            'segment_type' => $this->segment_type,
            'segment_number' => $this->segment_number,
            'segment_label' => $this->segment_label,
            'segment_subtype' => $this->segment_subtype,
            'segment_subnumber' => $this->segment_subnumber,

            'thumbnail_url' => $this->thumbnail_url,
            'detail_url' => $this->when($request->routeIs('content-items.show'), $this->detail_url),
            'start_date' => $this->start_date?->toDateTimeString(),
            'end_date' => $this->end_date?->toDateTimeString(),
            'current_progress' => $this->current_progress,
            'total_progress' => $this->total_progress,
            'progress_percent' => $this->progress_percentage,
            'progress_unit' => $this->progress_unit->value ?? null,
            'notes' => $this->notes,
            'rating' => $this->rating,
            'is_active' => $this->is_active,

            'tags' => $this->whenLoaded(
                'tags',
                fn() =>
                $this->tags->map(fn($tag) => [
                    'id' => $tag->id,
                    'name' => $tag->name,
                ])
            ),

            'type' => new ContentTypeLiteResource($this->whenLoaded('contentType')),
            'progress_status' => new ProgressStatusLiteResource($this->whenLoaded('progressStatus')),
        ];
    }
}
