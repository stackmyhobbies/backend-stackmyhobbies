<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class contentItem extends Model
{
    //

    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'content_tag', 'content_item_id', 'tag_id')
            ->withTimestamps()
            ->withPivot('status');
    }
}
