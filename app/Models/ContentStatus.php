<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContentStatus extends Model
{
    //
    public function contentItems()
    {
        return $this->hasMany(ContentItem::class, 'status_id');
    }
}
