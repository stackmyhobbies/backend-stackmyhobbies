<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Model;

class ContentType extends Model
{
    use Sluggable;

    protected $table = 'content_types';

    protected $fillable = [
        'name',
        'slug',
        'status',
        'allowed_units',
        'allowed_segment_types',
        'allowed_subsegment_types',
    ];

    protected $casts = [
        'status' => 'boolean',
        'allowed_units' => 'array',
        'allowed_segment_types' => 'array',
        'allowed_subsegment_types' => 'array',
    ];

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'name',
                'onUpdate' => true,
            ],
        ];
    }

    public function setNameAttribute($value)
    {
        $this->attributes['name'] = strtolower($value);
    }

    public function contentItems()
    {
        return $this->hasMany(ContentItem::class, 'content_type_id');
    }
}
