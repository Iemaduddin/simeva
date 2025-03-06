<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Calendar extends Model
{
    protected $fillable = [
        'type',
        'event_id',
        'asset_booking_id',
        'is_public',
        'all_day',
        'background_color',
        'text_color'
    ];

    protected $casts = [
        'is_public' => 'boolean',
        'all_day' => 'boolean',
        'type' => 'string'
    ];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function asset_booking()
    {
        return $this->belongsTo(AssetBooking::class);
    }
}
