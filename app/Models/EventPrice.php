<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EventPrice extends Model
{
    protected $fillable = [
        'event_id',
        'participant_category',
        'price'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'participant_category' => 'string'
    ];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }
}