<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PropertyDetail extends Model
{
    protected $fillable = ['property_id', 'field_values'];
    protected $casts = [
        'field_values' => 'array'
    ];

    public function property()
    {
        return $this->belongsTo(Property::class);
    }
}