<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ViewingTimeSlot extends Model
{
    protected $fillable = [
        'viewing_request_id',
        'proposed_date_time',
        'is_selected'
    ];

    protected $casts = [
        'proposed_date_time' => 'datetime',
        'is_selected' => 'boolean'
    ];

    public function viewingRequest(): BelongsTo
    {
        return $this->belongsTo(ViewingRequest::class);
    }
}