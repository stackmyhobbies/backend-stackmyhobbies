<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContentType extends Model
{
    protected $table = "content_types";

    protected $fillable = [
        "name",
        "status"
    ];

    protected $casts = [
        'status' => 'boolean'
    ];
    //

    public function setNameAttribute($value)
    {
        $this->attributes['name'] = strtolower($value);
    }
    // Relación: un ContentType tiene muchos ContentItems
    public function contentItems()
    {
        return $this->hasMany(ContentItem::class, 'type_id');
    }
}
