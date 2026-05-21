<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Model;

class ProgressStatus extends Model
{
    use Sluggable;

    protected $table = 'progress_statuses';

    protected $fillable = [
        'name',
        'slug',
        'status',
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

    public function contentItems()
    {
        return $this->hasMany(ContentItem::class, 'progress_status_id');
    }
}
