<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Timeline extends Model
{
    protected $fillable = [
        'event_id',
        'title',
        'description',
        'date_time'
    ];

    protected $casts = [
        'date_time' => 'datetime'
    ];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }
}
