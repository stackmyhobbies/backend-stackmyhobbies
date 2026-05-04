<?php

namespace App\Models;

use App\Enums\DayOfWeek;
use App\Enums\ProgressUnit;
use App\Enums\SegmentType;
use App\Enums\SubSegmentType;
use Cloudinary\Transformation\Delivery;
use Cloudinary\Transformation\Format;
use Cloudinary\Transformation\Quality;
use Cloudinary\Transformation\Resize;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Model;

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
        'segment_subtype' => SubSegmentType::class,
        'day_of_week' => DayOfWeek::class,
        'status' => 'boolean',
        'viewing_started_at' => 'datetime',
        'viewing_finished_at' => 'datetime',
        'aired_from' => 'date',
        'aired_to' => 'date',
    ];

    protected $appends = [
        'thumbnail_url',
        'detail_url',
        'progress_percentage',
        'segment_label',
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
        return $this->segment_subtype ? $this->segment_subtype->value : null;
    }

    // App\Models\ContentItem.php

    public function getThumbnailUrlAttribute()
    {
        if (! $this->image_path) {
            return asset('images/default-placeholder.png');
        }

        // Si ya lo calculamos en esta ejecución, lo devolvemos
        if ($this->thumbnailCache) {
            return $this->thumbnailCache;
        }

        return $this->thumbnailCache = (string) Cloudinary::image($this->image_path)
            ->resize(Resize::fill(56, 56))
            ->delivery(Delivery::format(Format::auto()))
            ->delivery(Delivery::quality(Quality::auto()));
    }

    public function getDetailUrlAttribute()
    {
        if (! $this->image_path) {
            return asset('images/default-placeholder.png');
        }

        return (string) Cloudinary::image($this->image_path)
            ->resize(Resize::limitFit(600))
            ->delivery(Delivery::format(Format::auto()))
            ->delivery(Delivery::quality(Quality::auto()));
    }
}