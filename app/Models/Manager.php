<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Manager extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'email',
        'phone',
        'address',
        'status',// active ,inactive
    ];

    public function region()
    {
        return $this->belongsTo(Region::class);
    }

    public function subRegion()
    {
        return $this->belongsTo(SubRegion::class);
    }

    public function properties()
    {
        return $this->hasMany(Property::class, 'region_id', 'region_id')
                    ->where('sub_region_id', $this->sub_region_id);
    }
}