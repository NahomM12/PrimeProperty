<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use CloudinaryLabs\CloudinaryLaravel\MediaAlly;

class Property extends Model
{
    use HasFactory, MediaAlly;

    protected $fillable = [
        'title',
        'description',
        'address',
        'price',
        'status',
        'owner', // owner id
        'property_use', // sale or rent 
        'property_type_id', 
        'latitude', 
        'longitude',
        'field_values',
    ];

    protected $casts = [
        'field_values' => 'array'
    ];

    public function propertyType()
    {
        return $this->belongsTo(PropertyType::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
