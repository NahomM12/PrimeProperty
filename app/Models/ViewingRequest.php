<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ViewingRequest extends Model
{
    protected $fillable = [
        'customer_id',
        'property_id',
        'message',
        'status'
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function property(): BelongsTo
    {
        return $this->belongsTo(Property::class);
    }

    public function timeSlots(): HasMany
    {
        return $this->hasMany(ViewingTimeSlot::class);
    }
}