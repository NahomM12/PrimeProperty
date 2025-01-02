<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Region extends Model
{
    use HasFactory;

    protected $fillable = [
        'region_name',
        
    ];

    
    public function subRegions()
    {
        return $this->hasMany(SubRegion::class);
    }
    public function properties()
    {
        return $this->hasMany(Property::class);
    }
}
