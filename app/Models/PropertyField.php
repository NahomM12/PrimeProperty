<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PropertyField extends Model
{
    protected $fillable = ['property_type_id', 'field_name', 'field_type'];

    public function propertyType()
    {
        return $this->belongsTo(PropertyType::class);
    }
}
