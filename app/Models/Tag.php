<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{

    use Sluggable;
    //
    protected $fillable = ['name', 'slug', 'status'];

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'name'
            ]
        ];
    }

    public function contentItems()
    {
        return $this->belongsToMany(ContentItem::class, 'content_tag', 'tag_id', 'content_item_id')
            ->withTimestamps()
            ->withPivot('status');
    }
}
