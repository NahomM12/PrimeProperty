<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'owner',
        'customer',
        'property_id',
        'transaction_type', // sale or rent
        'transaction_date',
        'price',
        'commission',
        'rent_start_date',
        'rent_end_date'
    ];

    protected $casts = [
        'transaction_date' => 'datetime',
        'rent_start_date' => 'datetime',
        'rent_end_date' => 'datetime',
    ];

    public function property()
    {
        return $this->belongsTo(Property::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function customer()
    {
        return $this->belongsTo(User::class, 'customer');
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner'); 
    }


}