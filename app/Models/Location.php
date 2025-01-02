<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    use HasFactory;

    protected $fillable = [
        'subregion_id', 
        'location',
    ];

    public function subregion()
    {
        return $this->belongsTo(SubRegion::class);
    }
    public function properties()
    {
        return $this->hasMany(Property::class);
    }
}
