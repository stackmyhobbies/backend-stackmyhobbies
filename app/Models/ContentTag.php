<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContentTag extends Model
{
    public $timestamps = true;

    protected $table = 'content_tag';

    protected $fillable = ['content_item_id', 'tag_id', 'status'];
}
