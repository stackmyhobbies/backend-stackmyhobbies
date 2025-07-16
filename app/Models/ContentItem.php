<?php

namespace App\Models;

use App\Enums\ProgressUnit;
use App\Enums\SegmentType;
use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;
use BackedEnum;

class ContentItem extends Model
{
    //
    use sluggable;
    protected $guarded = [];

    public function sluggable(): array
    {

        return [
            'slug' => [
                'onUpdate' => true,
                'source' => ['title', 'segment_type', 'segment_number']

            ],
        ];
    }

    protected $casts = [
        'progress_unit' => ProgressUnit::class,
        'status' => 'boolean',
        'start_date' => 'datetime',
        'end_date' => 'datetime',

    ];
    // Cada ContentItem pertenece a un User
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    // Relación: cada ContentItem pertenece a un ContentType
    public function contentType()
    {
        return $this->belongsTo(ContentType::class, 'type_id');
    }

    // Cada ContentItem pertenece a un ContentStatus
    public function contentStatus()
    {
        return $this->belongsTo(ContentStatus::class, 'status_id');
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
        $type = $this->segment_type;

        return $type && $this->segment_number
            ? "{$type} {$this->segment_number}"
            : null;
    }
}
