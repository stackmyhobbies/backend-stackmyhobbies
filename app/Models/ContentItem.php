<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;

use App\Enums\ProgressUnit;
use App\Enums\SegmentType;
use App\Enums\SubSegmentType;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

class ContentItem extends Model
{
    use Sluggable;

    protected $guarded = [];

    public function sluggable(): array
    {
        return [
            'slug' => [
                'onUpdate' => true,
                'source' => ['title', 'segment_type_for_slug', 'segment_number', 'sub_segment_type_for_slug', 'segment_subnumber'],
            ],
        ];
    }

    protected $casts = [
        'progress_unit' => ProgressUnit::class,
        'segment_type' => SegmentType::class,
        'subsegment_type' => SubSegmentType::class,
        'status' => 'boolean',
        'start_date' => 'datetime',
        'end_date' => 'datetime',
    ];

    protected $appends = [
        'thumbnail_url',
        'detail_url',
        'progress_percentage',
        'segment_label'
    ];

    // En tu modelo ContentItem.php

    protected $thumbnailCache = null;



    // Cada ContentItem pertenece a un User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relación: cada ContentItem pertenece a un ContentType
    public function contentType()
    {
        return $this->belongsTo(ContentType::class, 'content_type_id');
    }

    // Cada ContentItem pertenece a un ProgressStatus
    public function progressStatus()
    {
        return $this->belongsTo(ProgressStatus::class, 'progress_status_id');
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'content_tag', 'content_item_id', 'tag_id')
            ->withTimestamps()
            ->withPivot('status')
            ->wherePivot('status', true);
    }

    public function getProgressPercentageAttribute(): ?float
    {
        if ($this->total_progress > 0) {
            return round(($this->current_progress / $this->total_progress) * 100, 2);
        }
        return null;
    }

    public function getSegmentLabelAttribute(): ?string
    {
        $label = $this->segment_type ? $this->segment_type->label() : null;

        return $label && $this->segment_number
            ? "{$label} {$this->segment_number}"
            : null;
    }

    // Método para convertir el enum a string para el slug
    public function getSegmentTypeForSlugAttribute()
    {
        return $this->segment_type ? $this->segment_type->value : null;
    }

    public function getSubSegmentTypeForSlugAttribute()
    {
        return $this->subsegment_type ? $this->subsegment_type->value : null;
    }

    // App\Models\ContentItem.php


    public function getThumbnailUrlAttribute()
    {
        if (!$this->image_path) return asset('images/default-placeholder.png');

        // Si ya lo calculamos en esta ejecución, lo devolvemos
        if ($this->thumbnailCache) return $this->thumbnailCache;

        return $this->thumbnailCache = Cloudinary::getUrl($this->image_path, [
            'width' => 56,
            'height' => 56,
            'crop' => 'fill',
            'gravity' => 'auto',
            'fetch_format' => 'auto',
            'quality' => 'auto'
        ]);
    }


    public function getDetailUrlAttribute()
    {
        if (!$this->image_path) return asset('images/default-placeholder.png');

        // Versión para el detalle (ejemplo 600px de ancho)
        return Cloudinary::getUrl($this->image_path, [
            'width' => 600,
            'crop' => 'limit',
            'fetch_format' => 'auto',
            'quality' => 'auto'
        ]);
    }
}
