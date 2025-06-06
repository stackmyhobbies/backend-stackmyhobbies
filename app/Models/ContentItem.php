<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContentItem extends Model
{
    //
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
            ->withPivot('status');
    }
}
