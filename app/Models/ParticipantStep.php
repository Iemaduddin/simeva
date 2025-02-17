<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ParticipantStep extends Model
{
    protected $fillable = [
        'user_id',
        'event_id',
        'event_step_id',
        'status'
    ];

    protected $casts = [
        'status' => 'string'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function eventStep()
    {
        return $this->belongsTo(EventStep::class);
    }
}
