<?php

namespace App\Http\Resources;

use App\Http\Resources\ContentType\ContentTypeLiteResource;
use App\Http\Resources\ProgressStatus\ProgressStatusLiteResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ContentItemResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        // Definimos si es la ruta de detalle para reutilizar la lógica
        $isDetail = $request->routeIs('content-item.show');

        return [
            'id' => $this->id,
            'title' => $this->title,
            'segment_label' => $this->segment_label,
            'thumbnail_url' => $this->thumbnail_url,

            // Datos de progreso (Los usas en la tabla)
            'current_progress' => $this->current_progress,
            'total_progress' => $this->total_progress,
            'progress_percent' => $this->progress_percentage,
            'progress_unit' => $this->progress_unit->value ?? null,

            // Relaciones (Siempre que estén cargadas con eager loading)
            'tags' => $this->whenLoaded('tags', fn() => $this->tags->map(fn($tag) => [
                'id' => $tag->id,
                'name' => $tag->name,
            ])),
            'type' => new ContentTypeLiteResource($this->whenLoaded('contentType')),
            'progress_status' => new ProgressStatusLiteResource($this->whenLoaded('progressStatus')),

            // === CAMPOS EXCLUSIVOS DEL DETALLE (SHOW) ===
            'slug' => $this->slug,
            'description' => $this->when($isDetail, $this->description),
            'notes' => $this->when($isDetail, $this->notes),
            'rating' => $this->when($isDetail, $this->rating),
            'user_id' => $this->when($isDetail, $this->user_id),
            'content_type_id' => $this->when($isDetail, $this->content_type_id),
            'progress_status_id' => $this->when($isDetail, $this->progress_status_id),

            // Fechas que no se ven en la tabla
            'viewing_started_at' => $this->when($isDetail, $this->viewing_started_at?->toDateTimeString()),
            'viewing_finished_at' => $this->when($isDetail, $this->viewing_finished_at?->toDateTimeString()),
            'aired_from' => $this->when($isDetail, $this->aired_from?->toDateString()),
            'aired_to' => $this->when($isDetail, $this->aired_to?->toDateString()),

            // Segmentación técnica (ya tienes el label en el index)
            'segment_type' => $this->when($isDetail, $this->segment_type),
            'segment_number' => $this->when($isDetail, $this->segment_number),
            'segment_subtype' => $this->when($isDetail, $this->segment_subtype),
            'segment_subnumber' => $this->when($isDetail, $this->segment_subnumber),

            'is_active' => $this->when($isDetail, $this->is_active),
            'day_of_week' => $this->when($isDetail, $this->day_of_week?->value),
        ];
    }
}
