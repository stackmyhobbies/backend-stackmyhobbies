<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContentType extends Model
{
    protected $table = "content_types";

    protected $fillable = [
        "name",
        "status",
        "allowed_units",
        "allowed_segment_types",
        "allowed_subsegment_types"
    ];

    protected $casts = [
        'status' => 'boolean',
        'allowed_units' => 'array',
        'allowed_segment_types' => 'array',
        'allowed_subsegment_types' => 'array'
    ];
    //

    public function setNameAttribute($value)
    {
        $this->attributes['name'] = strtolower($value);
    }
    // Relación: un ContentType tiene muchos ContentItems
    public function contentItems()
    {
        return $this->hasMany(ContentItem::class, 'content_type_id');
    }
}
