<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContentType extends Model
{
    //
    // Relación: un ContentType tiene muchos ContentItems
    public function contentItems()
    {
        return $this->hasMany(ContentItem::class, 'type_id');
    }
}
