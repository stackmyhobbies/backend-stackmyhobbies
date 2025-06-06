<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    //

    public function contentItems()
    {
        return $this->belongsToMany(ContentItem::class, 'content_tag', 'tag_id', 'content_item_id')
            ->withTimestamps()
            ->withPivot('status');
    }
}
