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
        'owner',
        'property_use',
        'property_type_id',
        'latitude',
        'longitude',
        'images',
        'field_values',
        'is_featured',
        'region_id',
        'subregion_id',
        'location_id',
        'bedrooms',
        'bathrooms'
    ];

    protected $casts = [
        'is_featured' => 'boolean',
        'images' => 'array',
        'field_values' => 'array'
    ];

    // Relationships
    public function propertyType()
    {
        return $this->belongsTo(PropertyType::class);
    }

    public function region()
    {
        return $this->belongsTo(Region::class);
    }

    public function subregion()
    {
        return $this->belongsTo(SubRegion::class);
    }

    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    public function views()
    {
        return $this->hasMany(PropertyView::class);
    }
    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
}