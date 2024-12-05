<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class Property extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'address',
        'bedrooms',
        'bathrooms',
        'price',
        'images',
        'status',
        'owner', // owner id
        'propertyUse', // sale or rent 
        'property_type_id', 
        'latitude', 
        'longitude',
        'field_values',
    ];

    protected $casts = [
        'images' => 'array',
        'field_values' => 'array'
    ];

    public function propertyType()
    {
        return $this->belongsTo(PropertyType::class);
    }
}