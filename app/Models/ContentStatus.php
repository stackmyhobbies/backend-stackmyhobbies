<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContentStatus extends Model
{
    protected $table = 'content_statuses';

    protected $fillable = [
        "name",
        "status"
    ];
    //
    public function contentItems()
    {
        return $this->hasMany(ContentItem::class, 'status_id');
    }
}
