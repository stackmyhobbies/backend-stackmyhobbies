<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProgressStatus extends Model
{
    protected $table = 'progress_statuses';

    protected $fillable = [
        "name",
        "status"
    ];

    public function contentItems()
    {
        return $this->hasMany(ContentItem::class, 'progress_status_id');
    }
}
