<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Calendar extends Model
{
    protected $fillable = [
        'type',
        'event_id',
        'asset_id',
        'title',
        'description',
        'start_date',
        'end_date',
        'is_public',
        'all_day',
        'background_color',
        'text_color'
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'is_public' => 'boolean',
        'all_day' => 'boolean',
        'type' => 'string'
    ];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function asset()
    {
        return $this->belongsTo(Asset::class);
    }
}