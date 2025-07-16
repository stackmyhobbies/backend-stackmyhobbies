<?php

namespace App\Http\Resources\ContentType;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ContentTypeLiteResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
        ];
    }
}
